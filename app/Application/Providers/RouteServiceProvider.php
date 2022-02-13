<?php

namespace App\Application\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    public const HOME = '/';

    public function boot()
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static fn (Request $request) => Limit::perMinute(config('app.rate_limits.api'))->by($request->user()?->id ?? $request->ip()));
        RateLimiter::for('hard', static fn (Request $request) => Limit::perMinute(config('app.rate_limits.hard'))->by($request->user()?->id ?? $request->ip()));
    }
}
