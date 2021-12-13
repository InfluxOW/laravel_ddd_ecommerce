<?php

namespace App\Application\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(fn (): Route => RouteFacade::fallback(fn (): RedirectResponse => redirect()->to(config('l5-swagger.documentations.default.routes.api'))));
    }
}
