<?php

namespace App\Domains\Catalog\Http\Controllers\Api;

use App\Domains\Catalog\Http\Resources\HeavyProductCategoryResource;
use App\Domains\Catalog\Models\ProductCategory;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    protected string $resource = HeavyProductCategoryResource::class;

    public function index(): AnonymousResourceCollection
    {
        ProductCategory::loadHeavyHierarchy();

        return $this->respondWithCollection(ProductCategory::getVisibleHierarchy());
    }
}
