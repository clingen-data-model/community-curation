<?php

namespace App\Console;

use App\Jobs\Dev\TestJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        if (env('TEST_SCHEDULER')) {
            $schedule->call(function () {
                \Log::info('testing scheduler');
                TestJob::dispatch();
            })->everyMinute();
        }

        $schedule->command('volunteers:notify-followup')
            ->dailyAt('01:00');
        $schedule->command('applications:clean')
            ->dailyAt('00:01');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
