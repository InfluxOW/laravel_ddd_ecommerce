<?php

namespace App\Domains\Catalog\Providers;

use App\Domains\Catalog\Http\Controllers\Api\ProductCategoryController;
use App\Domains\Catalog\Http\Controllers\Api\ProductController;
use App\Domains\Catalog\Http\Middleware\SetDefaultCurrency;
use App\Infrastructure\Abstracts\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->apiResource('categories', ProductCategoryController::class)->only(['index']);
        $router->middleware([SetDefaultCurrency::class])->apiResource('products', ProductController::class)->only(['index', 'show'])->parameters(['products' => 'product:slug']);
    }
}
