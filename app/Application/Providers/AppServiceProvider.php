<?php

namespace App\Application\Providers;

use Illuminate\Database\Eloquent\Model;
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
    }

    public function boot(): void
    {
        $this->preventLazyLoading();
    }

    private function preventLazyLoading(): void
    {
        if (app()->isProduction()) {
            return;
        }

        Model::preventLazyLoading();
    }
}
