<?php

namespace App\Domains\Feedback\Providers;

use App\Domains\Feedback\Http\Controllers\Api\FeedbackController;
use App\Infrastructure\Abstracts\RouteServiceProviderBase;
use Illuminate\Routing\Router;

class RouteServiceProvider extends RouteServiceProviderBase
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->apiResource('feedback', FeedbackController::class)->only(['store']);
    }
}
