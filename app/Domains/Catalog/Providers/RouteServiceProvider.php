<?php

namespace App\Domains\Catalog\Providers;

use App\Application\Http\Middleware\SetDefaultCurrency;
use App\Domains\Catalog\Http\Controllers\Api\ProductCategoryController;
use App\Domains\Catalog\Http\Controllers\Api\ProductController;
use App\Infrastructure\Abstracts\RouteServiceProviderBase;
use Illuminate\Routing\Router;

class RouteServiceProvider extends RouteServiceProviderBase
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->apiResource('categories', ProductCategoryController::class)->only(['index']);
        $router->middleware([SetDefaultCurrency::class])->apiResource('products', ProductController::class)->only(['index', 'show'])->parameters(['products' => 'product:slug']);
    }
}
