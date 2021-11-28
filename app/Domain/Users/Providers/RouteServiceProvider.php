<?php

namespace App\Domain\Users\Providers;

use App\Domain\Users\Http\Controllers\Api\LoginController;
use App\Domain\Users\Http\Controllers\Api\LogoutController;
use App\Domain\Users\Http\Controllers\Api\RegisterController;
use App\Infrastructure\Abstracts\RouteServiceProviderBase;
use Illuminate\Routing\Router;

class RouteServiceProvider extends RouteServiceProviderBase
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->middleware(['guest'])->group(function () use ($router): void {
            $router->post('login', LoginController::class)->name('login');
            $router->post('register', RegisterController::class)->name('register');
        });

        $router->middleware(['auth:sanctum'])->group(function () use ($router): void {
            $router->post('logout', LogoutController::class)->name('logout');
        });
    }
}
