<?php

namespace App\Domains\Catalog\Http\Controllers\Api\Virtual;

use OpenApi\Annotations as OA;

final class ProductCategoryController
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
     *       @OA\Property(
     *          property="data",
     *          type="array",
     *          collectionFormat="multi",
     *          @OA\Items(
     *             type="object",
     *             ref="#/components/schemas/HeavyProductCategory",
     *          ),
     *       ),
     *    ),
     * ),
     * )
     */
    public function index(): void
    {
        //
    }
}
