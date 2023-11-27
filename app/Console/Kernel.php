<?php

namespace App\Console;

use App\Jobs\FetchNewsJob;
use App\Jobs\Helpers\HourlyCommand;
use App\Models\Category;
use App\Models\Country;
use App\NewsSources\NewsApiOrgHelper;
use App\NewsSources\TheGuardianApiHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            HourlyCommand::fetchNewsHourlyCommand();
        })->hourly()->name('newsFetching')->withoutOverlapping();
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
