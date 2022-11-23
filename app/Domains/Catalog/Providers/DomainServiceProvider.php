<?php

namespace App\Domains\Catalog\Providers;

use App\Domains\Catalog\Console\Commands\UpdateProductCategoriesDisplayability;
use App\Domains\Catalog\Console\Commands\UpdateProductsDisplayability;
use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::CATALOG;

    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
    ];

    protected array $commands = [
        UpdateProductsDisplayability::class,
        UpdateProductCategoriesDisplayability::class,
    ];

    protected function registerSchedule(Schedule $schedule): void
    {
        $schedule->command(UpdateProductCategoriesDisplayability::class)->cron('* * * * *')->runInBackground()->environments(['local', 'staging']);
        $schedule->command(UpdateProductsDisplayability::class)->cron('* * * * *')->runInBackground()->environments(['local', 'staging']);
    }
}
