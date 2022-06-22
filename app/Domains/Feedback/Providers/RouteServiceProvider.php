<?php

namespace App\Domains\Feedback\Providers;

use App\Domains\Feedback\Http\Controllers\Api\FeedbackController;
use App\Infrastructure\Abstracts\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->middleware(['recaptcha'])->group(function () use ($router): void {
            $router->apiResource('feedback', FeedbackController::class)->only(['store']);
        });
    }
}
