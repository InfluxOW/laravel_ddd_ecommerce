<?php

namespace App\Domains\News\Http\Controllers\Api\Virtual;

use OpenApi\Annotations as OA;

final class ArticleController
{
    /**
     * @OA\Get(
     *    path="/news",
     *    summary="News Index",
     *    description="View all published news",
     *    operationId="newsIndex",
     *    tags={"News"},
     *    @OA\Parameter(
     *       name="filter[SEARCH]",
     *       in="query",
     *       description="Search news by custom query",
     *       required=false,
     *       @OA\Schema(
     *          type="string"
     *       ),
     *    ),
     *    @OA\Parameter(
     *       name="filter[PUBLISHED_BETWEEN]",
     *       in="query",
     *       description="Filter news by published date. Requires two comma separated values in RFC3339 format.",
     *       explode=true,
     *       required=false,
     *       example=",2022-08-03T04:09:05+00:00",
     *       @OA\Schema(
     *          type="string",
     *       ),
     *    ),
     *    @OA\Parameter(
     *       name="sort",
     *       in="query",
     *       description="Sort news by one of the available params.",
     *       required=false,
     *       @OA\Schema(ref="#/components/schemas/ArticleAllowedSort"),
     *    ),
     *    @OA\Parameter(
     *       name="page",
     *       in="query",
     *       description="Results page",
     *       required=false,
     *       @OA\Schema(
     *          type="integer"
     *       ),
     *    ),
     *    @OA\Parameter(
     *      name="per_page",
     *      in="query",
     *      description="Results per page",
     *      required=false,
     *      @OA\Schema(
     *         type="integer"
     *      )
     *    ),
     *    @OA\Response(
     *       response=200,
     *       description="News were fetched",
     *       @OA\JsonContent(
     *          @OA\Property(
     *             property="data",
     *             type="array",
     *             collectionFormat="multi",
     *             @OA\Items(
     *                type="object",
     *                ref="#/components/schemas/LightArticle",
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
     *                @OA\Property(
     *                   property="applied",
     *                   type="object",
     *                   @OA\Property(property="query", ref="#/components/schemas/ArticleAllowedSort"),
     *                   @OA\Property(property="title", type="string", example="Title A-Z"),
     *                ),
     *                @OA\Property(
     *                   property="allowed",
     *                   type="array",
     *                   collectionFormat="multi",
     *                   @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="query", ref="#/components/schemas/ArticleAllowedSort"),
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
     *                   @OA\Items(
     *                      type="object",
     *                      oneOf={
     *                         @OA\Schema(ref="#/components/schemas/AppliedInputFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedSelectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedRangeFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedPlainMultiselectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AppliedNestedMultiselectFilter"),
     *                      }
     *                   ),
     *                ),
     *                @OA\Property(
     *                   property="allowed",
     *                   type="array",
     *                   collectionFormat="multi",
     *                   @OA\Items(
     *                      type="object",
     *                      oneOf={
     *                         @OA\Schema(ref="#/components/schemas/AllowedInputFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedSelectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedRangeFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedPlainMultiselectFilter"),
     *                         @OA\Schema(ref="#/components/schemas/AllowedNestedMultiselectFilter"),
     *                      }
     *                   ),
     *                ),
     *             ),
     *          )
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
     *             property="filter.PUBLISHED_BETWEEN",
     *             type="array",
     *             collectionFormat="multi",
     *             @OA\Items(
     *                type="string",
     *                example="The filter.PUBLISHED_BETWEEN must contain 2 items.",
     *             ),
     *          ),
     *       ),
     *    ),
     *    )
     * )
     */
    public function index(): void
    {
        //
    }

    /**
     * @OA\Get(
     *    path="/news/{article:slug}",
     *    summary="News Show",
     *    description="View a specific article",
     *    operationId="newsShow",
     *    tags={"News"},
     *    @OA\Parameter(
     *       name="article:slug",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       ),
     *    ),
     *    @OA\Response(
     *       response=200,
     *       description="Specified article has been found",
     *       @OA\JsonContent(
     *          @OA\Property(property="data", type="object", ref="#/components/schemas/HeavyArticle"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Specified article has not been found",
     *       @OA\JsonContent(),
     *    ),
     * )
     */
    public function show(): void
    {
        //
    }
}
