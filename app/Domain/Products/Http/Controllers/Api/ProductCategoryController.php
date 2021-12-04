<?php

namespace App\Domain\Products\Http\Controllers\Api;

use App\Domain\Products\Http\Resources\HeavyProductCategoryResource;
use App\Domain\Products\Models\ProductCategory;
use App\Interfaces\Http\Controllers\Controller;
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
     *       ref="#/components/schemas/HeavyProductCategory",
     *     ),
     *    ),
     *   ),
     *  ),
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = ProductCategory::query()->withCount(['products'])->get()->toHierarchy();

        return HeavyProductCategoryResource::collection($categories);
    }
}
