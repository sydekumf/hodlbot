<?php

namespace App\Console;

use App\Console\Commands\UpdateAthCommand;
use App\Console\Commands\UpdateBalanceSnapshotCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(UpdateBalanceSnapshotCommand::class)
            ->daily()
            ->timezone('UTC');

        $schedule->command(UpdateAthCommand::class)
            ->daily()
            ->timezone('UTC');
    }

    /**
     * @return string
     */
    protected function scheduleTimezone()
    {
        return 'Europe/Berlin';
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
