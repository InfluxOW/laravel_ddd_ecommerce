<?php

namespace App\Domains\Users\Providers;

use App\Domains\Common\Utils\PathUtils;
use App\Domains\Users\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Domains\Users\Http\Controllers\Api\Auth\LoginController;
use App\Domains\Users\Http\Controllers\Api\Auth\LogoutController;
use App\Domains\Users\Http\Controllers\Api\Auth\RegisterController;
use App\Infrastructure\Abstracts\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->middleware(['guest'])->group(function () use ($router): void {
            $router->middleware(['recaptcha'])->group(function () use ($router): void {
                $router->post('login', LoginController::class)->name('login');
                $router->post('register', RegisterController::class)->name('register');
            });

            $router->middleware(['throttle:hard'])->group(function () use ($router): void {
                $router->post(PathUtils::join(['user', 'verify', 'email']), EmailVerificationController::class)->name('user.verify.email');
            });
        });

        $router->middleware(['auth:sanctum'])->group(function () use ($router): void {
            $router->post('logout', LogoutController::class)->name('logout');
        });
    }
}
