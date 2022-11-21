<?php

namespace App\Infrastructure\Abstracts\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

abstract class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    public function map(Router $router): void
    {
        $router
            ->group([
                'namespace' => $this->namespace,
                'prefix' => 'api',
                'middleware' => ['api'],
            ], function (Router $router): void {
                $this->mapApiRoutes($router);
            });

        $router
            ->group([
                'namespace' => $this->namespace,
                'middleware' => ['web'],
            ], function (Router $router): void {
                $this->mapWebRoutes($router);
            });
    }

    protected function mapApiRoutes(Router $router): void
    {
        //
    }

    protected function mapWebRoutes(Router $router): void
    {
        //
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        //
    }
}
