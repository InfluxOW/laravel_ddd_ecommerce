<?php

namespace App\Domain\Catalog\Http\Controllers\Api;

use App\Domain\Catalog\Http\Resources\HeavyProductCategoryResource;
use App\Domain\Catalog\Models\ProductCategory;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    protected string $resource = HeavyProductCategoryResource::class;

    public function index(): AnonymousResourceCollection
    {
        ProductCategory::loadHeavyHierarchy();
        $hierarchy = ProductCategory::filterHierarchy(static fn (ProductCategory $category): bool => $category->is_visible, ProductCategory::$hierarchy);

        return $this->respondWithCollection($hierarchy);
    }
}
