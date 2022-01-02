<?php

namespace App\Domain\Catalog\Providers;

use App\Domain\Catalog\Http\Controllers\Api\ProductCategoryController;
use App\Domain\Catalog\Http\Controllers\Api\ProductController;
use App\Infrastructure\Abstracts\RouteServiceProviderBase;
use Illuminate\Routing\Router;

class RouteServiceProvider extends RouteServiceProviderBase
{
    protected function mapApiRoutes(Router $router): void
    {
        $router->apiResource('categories', ProductCategoryController::class)->only(['index']);
        $router->apiResource('products', ProductController::class)->only(['index', 'show'])->parameters(['products' => 'product:slug']);
    }
}
