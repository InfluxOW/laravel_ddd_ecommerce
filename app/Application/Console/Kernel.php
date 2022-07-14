<?php

namespace App\Application\Console;

use App\Domains\Catalog\Console\Commands\UpdateProductCategoriesDisplayability;
use App\Domains\Catalog\Console\Commands\UpdateProductsDisplayability;
use App\Domains\Generic\Console\Commands\RefreshApplicationCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Telescope\Console\PruneCommand;

final class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(PruneCommand::class)->cron('0 0 * * *')->runInBackground()->environments(['staging']);
        $schedule->command(RefreshApplicationCommand::class)->cron('0 1 * * *')->runInBackground()->environments(['staging']);

        $schedule->command(UpdateProductCategoriesDisplayability::class)->cron('*/2 * * * *')->runInBackground()->environments(['local', 'staging']);
        $schedule->command(UpdateProductsDisplayability::class)->cron('1-59/2 * * * *')->runInBackground()->environments(['local', 'staging']);
    }
}
