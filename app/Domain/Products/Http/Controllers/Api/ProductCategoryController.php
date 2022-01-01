<?php

namespace App\Domain\Products\Http\Controllers\Api;

use App\Domain\Products\Http\Resources\HeavyProductCategoryResource;
use App\Domain\Products\Models\ProductCategory;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    protected string $resource = HeavyProductCategoryResource::class;

    public function index(): AnonymousResourceCollection
    {
        ProductCategory::loadHeavyHierarchy();

        return $this->respondWithCollection(ProductCategory::$hierarchy);
    }
}
