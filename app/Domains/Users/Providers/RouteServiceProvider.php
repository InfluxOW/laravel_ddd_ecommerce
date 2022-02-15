<?php

namespace App\Domains\Users\Providers;

use App\Domains\Generic\Utils\PathUtils;
use App\Domains\Users\Http\Controllers\Api\EmailVerificationController;
use App\Domains\Users\Http\Controllers\Api\LoginController;
use App\Domains\Users\Http\Controllers\Api\LogoutController;
use App\Domains\Users\Http\Controllers\Api\RegisterController;
use App\Infrastructure\Abstracts\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->middleware(['guest'])->group(function () use ($router): void {
            $router->post('login', LoginController::class)->name('login');
            $router->post('register', RegisterController::class)->name('register');

            $router->middleware(['throttle:hard'])->group(function () use ($router): void {
                $router->post(PathUtils::join(['user', 'verify', 'email']), EmailVerificationController::class)->name('user.verify.email');
            });
        });

        $router->middleware(['auth:sanctum'])->group(function () use ($router): void {
            $router->post('logout', LogoutController::class)->name('logout');
        });
    }
}
