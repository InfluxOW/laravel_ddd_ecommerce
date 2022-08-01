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
     *       ),
     *    ),
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
