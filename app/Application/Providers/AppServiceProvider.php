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
        $this->app->register(DatabaseServiceProvider::class);

        $this->registerVendorServiceProviders();
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

        foreach ($this->getVendorServiceProviders() as $provider) {
            if (method_exists($provider, 'preventLazyLoading')) {
                $provider->preventLazyLoading();
            }
        }
    }

    /**
     * @return ServiceProvider[]
     */
    private function registerVendorServiceProviders(): array
    {
        $providers = [];
        $providers[] = $this->app->register(HorizonServiceProvider::class);
        $providers[] = $this->app->register(TelescopeServiceProvider::class);
        $providers[] = $this->app->register(TotemServiceProvider::class);
        $providers[] = $this->app->register(FakerServiceProvider::class);

        return $providers;
    }

    /**
     * @return ServiceProvider[]
     */
    private function getVendorServiceProviders(): array
    {
        return $this->registerVendorServiceProviders();
    }
}
