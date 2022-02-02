<?php

namespace App\Infrastructure\Abstracts;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;

abstract class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function boot()
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
    }

    abstract protected function mapApiRoutes(Router $router): void;

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static fn (Request $request) => Limit::perMinute(config('app.rate_limits.api'))->by($request->user()?->id ?? $request->ip()));
    }
}
