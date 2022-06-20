<?php

namespace App\Application\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(HorizonServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
        $this->app->register(TotemServiceProvider::class);
    }

    public function boot(): void
    {
        $this->preventLazyLoading();

        ParallelTesting::setUpTestDatabase(static fn () => Artisan::call('migrate:fresh'));
    }

    private function preventLazyLoading(): void
    {
        if (app()->isProduction()) {
            return;
        }

        Model::preventLazyLoading();
    }
}
