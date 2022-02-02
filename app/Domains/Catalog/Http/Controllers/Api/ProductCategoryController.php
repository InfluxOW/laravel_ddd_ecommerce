<?php

namespace App\Domains\Catalog\Http\Controllers\Api;

use App\Domains\Catalog\Http\Resources\ProductCategory\HeavyProductCategoryResource;
use App\Domains\Catalog\Models\ProductCategory;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ProductCategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return $this->respondWithCollection(HeavyProductCategoryResource::class, ProductCategory::getVisibleHierarchy());
    }
}
