<?php

namespace App\Domains\News\Providers;

use App\Domains\News\Http\Controllers\Api\ArticleController;
use App\Infrastructure\Abstracts\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->apiResource('news', ArticleController::class)->only(['index', 'show'])->parameters(['news' => 'article:slug']);
    }
}
