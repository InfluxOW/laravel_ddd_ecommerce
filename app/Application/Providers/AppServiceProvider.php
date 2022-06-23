<?php

namespace App\Application\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerVendorServiceProviders();
    }

    public function boot(): void
    {
        $this->preventLazyLoading();

        ParallelTesting::setUpTestDatabase(static fn () => Artisan::call('migrate:fresh'));

        RequestFacade::macro('getIp', function (): ?string {
            /** @var Request $request */
            $request = $this;

            $remotesKeys = [
                'HTTP_X_FORWARDED_FOR',
                'HTTP_CLIENT_IP',
                'HTTP_X_REAL_IP',
                'HTTP_X_FORWARDED',
                'X-FORWARDED-FOR',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR',
                'HTTP_X_CLUSTER_CLIENT_IP',
            ];

            foreach ($remotesKeys as $key) {
                $address = $request->header($key) ?? getenv($key);

                if (is_string($address)) {
                    foreach (explode(',', $address) as $ip) {
                        $ip = trim($ip);

                        if (
                            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false &&
                            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE) === false
                        ) {
                            continue;
                        }

                        return $ip;
                    }
                }
            }

            return $request->ip();
        });
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
