<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * The console kernel registers artisan commands and schedules.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // -------------------------------------------------
        //  Register your custom command here
        // -------------------------------------------------
        \App\Console\Commands\ScrapePitchingStats::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Example: run the scraper every day at midnight
        // $schedule->command('scrape:pitching')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // Include the routes file for console routes (if you have any)
        require base_path('routes/console.php');
    }
}