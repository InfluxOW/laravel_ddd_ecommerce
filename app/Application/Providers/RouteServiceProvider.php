<?php

namespace App\Application\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(fn (): Route => RouteFacade::fallback(static fn (): RedirectResponse => redirect()->to(config('l5-swagger.documentations.default.routes.api'))));
    }
}
