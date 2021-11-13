<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    /**
     * @OA\Get(
     * path="/categories",
     * summary="Categories Index",
     * description="View categories tree",
     * operationId="categoriesIndex",
     * tags={"Categories"},
     * @OA\Response(
     *    response=200,
     *    description="Categories were fetched",
     *    @OA\JsonContent(
     *    @OA\Property(
     *     property="data",
     *     type="array",
     *     collectionFormat="multi",
     *     @OA\Items(
     *       type="object",
     *       ref="#/components/schemas/ProductCategory",
     *     ),
     *    ),
     *   ),
     *  ),
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = ProductCategory::roots()
            ->get()
            ->map(function (ProductCategory $category): ProductCategory {
                // Total DB queries count equals to the number of root categories + 1
                return $category->getDescendantsAndSelf()->toHierarchy()->first();
            });

        return ProductCategoryResource::collection($categories);
    }
}
