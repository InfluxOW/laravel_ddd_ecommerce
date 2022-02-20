<?php

namespace App\Application\Console;

use App\Domains\Generic\Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Redis;

final class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('geoip:update')->daily()->runInBackground();

        $this->refreshDatabase($schedule);
    }

    private function refreshDatabase(Schedule $schedule): void
    {
        if (app()->isProduction()) {
            return;
        }

        $schedule->call(function (): void {
            $this->call('media-library:clear');
            $this->call('migrate:fresh');
            Redis::flushall();
            $this->call('db:seed', ['--class' => DatabaseSeeder::class]);
        })->daily()->runInBackground();
    }
}
