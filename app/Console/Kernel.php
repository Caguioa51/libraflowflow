<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SmokeBorrowTest;
use App\Console\Commands\SendOverdueNotifications;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SmokeBorrowTest::class,
        SendOverdueNotifications::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Send overdue notifications daily at 9:00 AM
        $schedule->command('library:send-overdue-notifications')
            ->dailyAt('09:00')
            ->timezone('Asia/Shanghai');
    }
}
