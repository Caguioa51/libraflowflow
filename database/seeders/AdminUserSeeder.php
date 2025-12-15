<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Only create admin if SEED_ADMIN_USER is true
        if (env('SEED_ADMIN_USER', false) !== 'true') {
            $this->command->info('SEED_ADMIN_USER is not set to true, skipping admin user creation.');
            return;
        }

        // Check if admin already exists
        $existingAdmin = DB::table('users')->where('email', 'admin@libraflow.com')->first();
        if ($existingAdmin) {
            $this->command->info('Admin user already exists, skipping creation.');
            return;
        }

        // Create admin user with secure defaults
        $adminData = [
            'email' => 'admin@libraflow.com',
            'name' => 'System Administrator',
            'role' => 'admin',
            'password' => Hash::make($this->generateSecurePassword()),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('users')->insert($adminData);
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@libraflow.com');
        $this->command->info('Password: ' . $this->generateSecurePassword());
    }

    private function generateSecurePassword(): string
    {
        // Generate a secure random password for production
        return 'Admin' . bin2hex(random_bytes(8)) . '!';
    }
}
