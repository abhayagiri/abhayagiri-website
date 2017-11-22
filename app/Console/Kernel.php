<?php

namespace App\Console;

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
        Commands\AddAdmin::class,
        Commands\BackupDatabase::class,
        Commands\BackupMedia::class,
        Commands\ExportDatabase::class,
        Commands\ExportMedia::class,
        Commands\FixLocalDirectories::class,
        Commands\ImportDatabase::class,
        Commands\ImportMedia::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $common = function($task)
        {
            $task->appendOutputTo(storage_path('logs/schedule.log'));
            $task->timezone('America/Los_Angeles');
            return $task;
        };

        $common($schedule->command('command:backup-database'))
            ->dailyAt('13:52');

        $common($schedule->command('command:backup-media'))
            ->dailyAt('13:52');

        $common($schedule->command('command:export-database'))
            ->dailyAt('13:52');

        $common($schedule->command('command:export-media'))
            ->dailyAt('13:52');

        $common($schedule->command('app:sync-gallery'))
            ->everyFifteenMinutes();
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
