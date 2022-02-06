<?php

namespace App\Domains\Generic\Providers;

use App\Infrastructure\Abstracts\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->fallback(static fn (): RedirectResponse => redirect()->to(config('l5-swagger.documentations.default.routes.api')));
    }
}
