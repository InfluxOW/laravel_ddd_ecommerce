<?php

namespace App\Domains\Generic\Providers;

use App\Infrastructure\Abstracts\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function map(Router $router): void
    {
        parent::map($router);

        $router->fallback(static fn (): RedirectResponse => redirect()->to(config('l5-swagger.documentations.default.routes.api')));
    }

    protected function mapApiRoutes(Router $router): void
    {
        //
    }
}
