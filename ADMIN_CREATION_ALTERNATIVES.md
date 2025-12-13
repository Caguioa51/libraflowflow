# Alternative Methods to Create Admin Account

Since the standard registration method isn't working, here are several alternative approaches:

## Method 1: Direct Database Insert (Most Reliable)

### Step 1: Access Your PostgreSQL Database
1. Go to your **Render Dashboard**
2. Click on your **PostgreSQL database**
3. Click **"Connect"** tab
4. Use **psql** command or **pgAdmin** to connect

### Step 2: Insert Admin User Directly
Run this SQL command in your database:

```sql
INSERT INTO users (
    name, 
    email, 
    email_verified_at, 
    password, 
    role, 
    created_at, 
    updated_at
) VALUES (
    'Administrator', 
    'admin@libraflow.com', 
    NOW(), 
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin', 
    NOW(), 
    NOW()
) ON CONFLICT (email) DO UPDATE SET 
    role = 'admin',
    updated_at = NOW();
```

**Password for this account**: `password123`

## Method 2: Modify Your App Temporarily

### Step 1: Add a Temporary Route
Add this route to your `routes/web.php` temporarily:

```php
// Temporary admin creation route - REMOVE AFTER USE
Route::get('/create-admin', function () {
    $user = new \App\Models\User();
    $user->name = 'Administrator';
    $user->email = 'admin@libraflow.com';
    $user->password = \Hash::make('admin123');
    $user->role = 'admin';
    $user->email_verified_at = now();
    $user->save();
    return 'Admin user created! Remove this route after use.';
});
```

### Step 2: Access the Route
1. Go to: `https://your-app.onrender.com/create-admin`
2. You should see: "Admin user created! Remove this route after use."
3. **Immediately remove** this route from your code after use

## Method 3: Modify Registration to Auto-Admin

### Temporary Registration Modification
Add this to your `app/Http/Controllers/Auth/RegisteredUserController.php` temporarily:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'admin', // Make first user admin
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
```

## Method 4: Use php artisan Command

### Create Artisan Command
Create `app/Console/Commands/CreateAdmin.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create admin user';

    public function handle()
    {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@libraflow.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->info('Admin user created successfully!');
        return 0;
    }
}
```

### Run Command on Render
Since you can't access shell, this won't work directly, but you could:
1. Temporarily add a route to run the command
2. Or run it locally and copy the user to production

## Method 5: Simple Database Seeder Route

### Add Simple Seeder Route
Add this to `routes/web.php` temporarily:

```php
// Simple seeder route - REMOVE AFTER USE
Route::get('/seed-admin', function () {
    $adminEmail = 'admin@libraflow.com';
    
    // Create admin user
    User::updateOrCreate(
        ['email' => $adminEmail],
        [
            'name' => 'Administrator',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]
    );

    return "Admin created! Use: {$adminEmail} / admin123";
});
```

### Use the Route
1. Go to: `https://your-app.onrender.com/seed-admin`
2. You'll see confirmation message
3. **Remove** this route after use

## Method 6: Check Current User Status

### View Current Users
Add this route to check what's in your database:

```php
Route::get('/debug-users', function () {
    $users = User::all();
    return $users->toArray();
});
```

## Recommended Approach

**I recommend Method 1 (Direct Database Insert)** as it's the most reliable:

1. **Access your Render PostgreSQL database**
2. **Run the SQL command** I provided above
3. **Login** with: admin@libraflow.com / password123
4. **Go to** `/admin/database-seeder` to run seeders
5. **Delete the route** you used to access the seeder

## Troubleshooting

### If SQL Command Fails
- Check if `users` table exists
- Verify column names match
- Make sure password hash is correct

### If App Still Doesn't Work
- Check if migrations ran successfully
- Verify database connection
- Check application logs in Render dashboard

### If Database Seeder Doesn't Work
- Make sure you're logged in as admin
- Check that user has `role = 'admin'`
- Verify the seeder routes are accessible

## Security Note

After creating your admin account:
1. **Change the password** immediately
2. **Remove any temporary routes** you added
3. **Test admin functionality** before going live
