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
     *     @OA\Parameter(
     *        name="filter[title]",
     *        in="query",
     *        description="Filter products by specific title",
     *        required=false,
     *        @OA\Schema(
     *           type="string"
     *        ),
     *     ),
     *     @OA\Parameter(
     *        name="filter[description]",
     *        in="query",
     *        description="Filter products by specific source",
     *        required=false,
     *        @OA\Schema(
     *           type="string"
     *        ),
     *     ),
     *     @OA\Parameter(
     *        name="filter[category]",
     *        in="query",
     *        description="Filter products by having one of the specific categories. Multiple values can be provided with comma separated strings.",
     *        required=false,
     *        example="first-category,second-category",
     *        @OA\Schema(
     *           type="string"
     *        ),
     *     ),
     *     @OA\Parameter(
     *        name="filter[currency]",
     *        in="query",
     *        description="Filter products by currency.",
     *        required=false,
     *        example="USD",
     *        @OA\Schema(
     *           type="string"
     *        ),
     *     ),
     *     @OA\Parameter(
     *        name="filter[price_between]",
     *        in="query",
     *        description="Filter products by price range. Requires two comma separated values.",
     *        explode=true,
     *        required=false,
     *        example="100,500",
     *        @OA\Schema(
     *           type="string",
     *        ),
     *     ),
     *     @OA\Parameter(
     *        name="filter[attribute]",
     *        in="query",
     *        description="Filter products by having one of the specific attribute values. Multiple values can be provided with comma separated strings.",
     *        required=false,
     *        example={"filter[attribute][width]": "20,30,50", "filter[attribute][height]": "50,60,70"},
     *        @OA\Schema(
     *           type="object",
     *        ),
     *     ),
     *     @OA\Parameter(
     *        name="sort",
     *        in="query",
     *        description="Sort products by one of the available params. Sorts with '-' symbol before the param name are the DESC sorts.",
     *        required=false,
     *        @OA\Schema(
     *           type="string",
     *           enum={"title", "created_at", "price", "-title", "-created_at", "-price"},
     *        ),
     *     ),
     *     @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="Results page",
     *        required=false,
     *        @OA\Schema(
     *           type="integer"
     *        ),
     *     ),
     *     @OA\Parameter(
     *       name="per_page",
     *       in="query",
     *       description="Results per page",
     *       required=false,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *    @OA\Response(
     *       response=200,
     *       description="Products were fetched",
     *       @OA\JsonContent(
     *          @OA\Property(
     *             property="data",
     *             type="array",
     *             collectionFormat="multi",
     *             @OA\Items(
     *               type="object",
     *               ref="#/components/schemas/LightProduct",
     *             ),
     *          ),
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
     *                @OA\Property(property="query", type="string", example="sort"),
     *                @OA\Property(property="is_nested", type="boolean", example="false"),
     *                @OA\Property(
     *                   property="applied",
     *                   type="array",
     *                   collectionFormat="multi",
     *                   @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="query", type="string", example="-price"),
     *                      @OA\Property(property="title", type="string", example="Expensive First"),
     *                   ),
     *                ),
     *                @OA\Property(
     *                   property="allowed",
     *                   type="array",
     *                   collectionFormat="multi",
     *                   @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="query", type="string", example="-price"),
     *                      @OA\Property(property="title", type="string", example="Expensive First"),
     *                   ),
     *                ),
     *             ),
     *             @OA\Property(
     *                property="filter",
     *                type="object",
     *                @OA\Property(property="query", type="string", example="filter"),
     *                @OA\Property(property="is_nested", type="boolean", example="true"),
     *                @OA\Property(
     *                   property="applied",
     *                   type="array",
     *                   collectionFormat="multi",
     *                   @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="query", type="string", example="title"),
     *                      @OA\Property(property="title", type="string", example="Title"),
     *                      @OA\Property(property="type", type="string", example="input"),
     *                   ),
     *                ),
     *                @OA\Property(
     *                   property="allowed",
     *                   type="array",
     *                   collectionFormat="multi",
     *                   @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="query", type="string", example="title"),
     *                      @OA\Property(property="title", type="string", example="Title"),
     *                      @OA\Property(property="type", type="string", example="input"),
     *                   ),
     *                ),
     *             ),
     *          ),
     *       ),
     *    ),
     *    @OA\Response(
     *    response=422,
     *    description="Validation Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *       property="errors",
     *       type="object",
     *          @OA\Property(
     *             property="filter.price_between",
     *             type="array",
     *             collectionFormat="multi",
     *             @OA\Items(
     *                type="string",
     *                example={"The filter.price_between must contain 2 items."},
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
     *    @OA\Parameter(
     *       name="product:slug",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       ),
     *    ),
     *    @OA\Response(
     *       response=200,
     *       description="Specified product has been found",
     *       @OA\JsonContent(
     *          @OA\Property(property="data", type="object", ref="#/components/schemas/HeavyProduct"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Specified product has not been found",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Product 'test' was not found."),
     *       ),
     *    ),
     * )
     */
    public function show(): void
    {
        //
    }
}
