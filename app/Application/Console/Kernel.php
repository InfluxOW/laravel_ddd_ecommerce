<?php

namespace App\Application\Console;

use App\Domains\Common\Console\Commands\RefreshApplicationCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(RefreshApplicationCommand::class)->cron('0 0 * * *')->runInBackground()->environments(['staging']);
    }
}
