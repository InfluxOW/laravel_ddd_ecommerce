<?php

namespace App\Domain\Products\Http\Controllers\Api\Virtual;

class ProductController
{
    /**
     * @OA\Get(
     * path="/products",
     * summary="Products Index",
     * description="View all products",
     * operationId="productsIndex",
     * tags={"Products"},
     *  @OA\Parameter(
     *    name="filter[title]",
     *    in="query",
     *    description="Filter products by specific title",
     *    required=false,
     *    @OA\Schema(
     *      type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[description]",
     *    in="query",
     *    description="Filter products by specific source",
     *    required=false,
     *    @OA\Schema(
     *      type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[category]",
     *    in="query",
     *    description="Filter products by having one of the specific categories",
     *    required=false,
     *    @OA\Schema(
     *      type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[price_between]",
     *    in="query",
     *    description="Filter products by price range",
     *    explode=true,
     *    required=false,
     *    example="100,500",
     *    @OA\Schema(
     *      type="string",
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[attribute]",
     *    in="query",
     *    description="Filter products by having one of the specific attribute values",
     *    required=false,
     *    example={"filter[attribute][width]": "20,30,50", "filter[attribute][height]": "50,60,70"},
     *    @OA\Schema(
     *      type="object",
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="sort",
     *    in="query",
     *    description="Sort products by one of the available params. Default sort direction is ASC. To apply DESC sort add '-' symbol before the param name.",
     *    required=false,
     *    @OA\Schema(
     *      type="string",
     *      enum={"title", "created_at", "price", "-title", "-created_at", "-price"},
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="page",
     *    in="query",
     *    description="Results page",
     *    required=false,
     *    @OA\Schema(
     *      type="integer"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="per_page",
     *    in="query",
     *    description="Results per page",
     *    required=false,
     *    @OA\Schema(
     *      type="integer"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Products were fetched",
     *     @OA\JsonContent(
     *       @OA\Property(
     *        property="data",
     *        type="object",
     *        collectionFormat="multi",
     *         @OA\Property(
     *         property="0",
     *         type="array",
     *         collectionFormat="multi",
     *         @OA\Items(
     *           type="object",
     *           ref="#/components/schemas/Product",
     *        )
     *       ),
     *    )
     *   )
     *  ),
     * )
     */
    public function index(): void
    {
        //
    }

    /**
     * @OA\Get(
     * path="/products/{product:slug}",
     * summary="Products Show",
     * description="View a specific product",
     * operationId="productsShow",
     * tags={"Products"},
     * @OA\Parameter(
     *    name="product:slug",
     *    in="path",
     *    required=true,
     *    @OA\Schema(
     *      type="string"
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Specified product has been found",
     *    @OA\JsonContent(
     *      type="object",
     *      ref="#/components/schemas/Product",
     *  ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Specified product has not been found",
     *  ),
     * )
     */
    public function show(): void
    {
        //
    }
}
