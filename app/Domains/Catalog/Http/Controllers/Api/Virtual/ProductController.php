<?php

namespace App\Domains\Catalog\Http\Controllers\Api\Virtual;

use OpenApi\Annotations as OA;

final class ProductController
{
    /**
     * @OA\Get(
     *    path="/products",
     *    summary="Products Index",
     *    description="View all products",
     *    operationId="productsIndex",
     *    tags={"Products"},
     *
     *    @OA\Parameter(
     *       name="filter[SEARCH]",
     *       in="query",
     *       description="Search products by custom query",
     *       required=false,
     *
     *       @OA\Schema(
     *          type="string"
     *       ),
     *    ),
     *
     *    @OA\Parameter(
     *       name="filter[CATEGORY]",
     *       in="query",
     *       description="Filter products by having one of the specific categories. Multiple values can be provided with comma separated strings.",
     *       required=false,
     *       example="first-category,second-category",
     *
     *       @OA\Schema(
     *          type="string"
     *       ),
     *    ),
     *
     *    @OA\Parameter(
     *       name="filter[CURRENCY]",
     *       in="query",
     *       description="Filter products by currency.",
     *       required=false,
     *       example="USD",
     *
     *       @OA\Schema(
     *          type="string"
     *       ),
     *    ),
     *
     *    @OA\Parameter(
     *       name="filter[PRICE_BETWEEN]",
     *       in="query",
     *       description="Filter products by price range. Requires two comma separated values.",
     *       explode=true,
     *       required=false,
     *       example="100,500",
     *
     *       @OA\Schema(
     *          type="string",
     *       ),
     *    ),
     *
     *    @OA\Parameter(
     *       name="filter[ATTRIBUTE]",
     *       in="query",
     *       description="Filter products by having one of the specific attribute values. Multiple values can be provided with comma separated strings.",
     *       required=false,
     *       example={"filter[ATTRIBUTE][width]": "20,30,50", "filter[ATTRIBUTE][height]": "50,60,70"},
     *
     *       @OA\Schema(
     *          type="object",
     *       ),
     *    ),
     *
     *    @OA\Parameter(
     *       name="sort",
     *       in="query",
     *       description="Sort products by one of the available params.",
     *       required=false,
     *
     *       @OA\Schema(ref="#/components/schemas/ProductAllowedSort"),
     *    ),
     *
     *    @OA\Parameter(
     *       name="page",
     *       in="query",
     *       description="Results page",
     *       required=false,
     *
     *       @OA\Schema(
     *          type="integer"
     *       ),
     *    ),
     *
     *    @OA\Parameter(
     *      name="per_page",
     *      in="query",
     *      description="Results per page",
     *      required=false,
     *
     *      @OA\Schema(
     *         type="integer"
     *      )
     *    ),
     *
     *    @OA\Response(
     *       response=200,
     *       description="Products were fetched",
     *
     *       @OA\JsonContent(
     *
     *          @OA\Property(
     *             property="data",
     *             type="array",
     *             collectionFormat="multi",
     *
     *             @OA\Items(
     *                type="object",
     *                ref="#/components/schemas/LightProduct",
     *             ),
     *          ),
     *
     *          @OA\Property(
     *             property="links",
     *             type="object",
     *             ref="#/components/schemas/PaginationLinks",
     *          ),
     *          @OA\Property(
     *             property="meta",
     *             type="object",
     *             ref="#/components/schemas/PaginationMeta",
     *          ),
     *          @OA\Property(
     *             property="query",
     *             type="object",
     *             @OA\Property(
     *                property="sort",
     *                type="object",
     *                @OA\Property(
     *                   property="applied",
     *                   type="object",
     *                   @OA\Property(property="query", ref="#/components/schemas/ProductAllowedSort"),
     *                   @OA\Property(property="title", type="string", example="Title A-Z"),
     *                ),
     *                @OA\Property(
     *                   property="allowed",
     *                   type="array",
     *                   collectionFormat="multi",
     *
     *                   @OA\Items(
     *                      type="object",
     *
     *                      @OA\Property(property="query", ref="#/components/schemas/ProductAllowedSort"),
     *                      @OA\Property(property="title", type="string", example="Title A-Z"),
     *                   ),
     *                ),
     *             ),
     *             @OA\Property(
     *                property="filter",
     *                type="object",
     *                @OA\Property(
     *                   property="applied",
     *                   type="array",
     *                   collectionFormat="multi",
     *
     *                   @OA\Items(
     *                      type="object",
     *                      oneOf={
     *
     *                         @OA\Schema(ref="#/components/schemas/AppliedInputFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedSelectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedRangeFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedPlainMultiselectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedNestedMultiselectFilter"),
     *                      }
     *                   ),
     *                ),
     *
     *                @OA\Property(
     *                   property="allowed",
     *                   type="array",
     *                   collectionFormat="multi",
     *
     *                   @OA\Items(
     *                      type="object",
     *                      oneOf={
     *
     *                         @OA\Schema(ref="#/components/schemas/AllowedInputFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedSelectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedRangeFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedPlainMultiselectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedNestedMultiselectFilter"),
     *                      }
     *                   ),
     *                ),
     *             ),
     *          ),
     *       ),
     *    ),
     *
     *    @OA\Response(
     *    response=422,
     *    description="Validation Error",
     *
     *    @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *       property="errors",
     *       type="object",
     *          @OA\Property(
     *             property="filter.PRICE_BETWEEN",
     *             type="array",
     *             collectionFormat="multi",
     *
     *             @OA\Items(
     *                type="string",
     *                example="The filter.PRICE_BETWEEN must contain 2 items.",
     *             ),
     *          ),
     *       ),
     *    ),
     *    ),
     * )
     */
    public function index(): void
    {
        //
    }

    /**
     * @OA\Get(
     *    path="/products/{product:slug}",
     *    summary="Products Show",
     *    description="View a specific product",
     *    operationId="productsShow",
     *    tags={"Products"},
     *
     *    @OA\Parameter(
     *       name="product:slug",
     *       in="path",
     *       required=true,
     *
     *       @OA\Schema(
     *          type="string"
     *       ),
     *    ),
     *
     *    @OA\Response(
     *       response=200,
     *       description="Specified product has been found",
     *
     *       @OA\JsonContent(
     *
     *          @OA\Property(property="data", type="object", ref="#/components/schemas/HeavyProduct"),
     *       ),
     *    ),
     *
     *    @OA\Response(
     *       response=404,
     *       description="Specified product has not been found",
     *
     *       @OA\JsonContent(),
     *    ),
     * )
     */
    public function show(): void
    {
        //
    }
}
