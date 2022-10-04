<?php

namespace App\Domains\News\Http\Controllers\Api;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\News\Database\Builders\ArticleBuilder;
use App\Domains\News\Http\Requests\ArticleIndexRequest;
use App\Domains\News\Http\Resources\Article\HeavyArticleResource;
use App\Domains\News\Http\Resources\Article\LightArticleResource;
use App\Domains\News\Models\Article;
use App\Domains\News\Services\Query\Filter\ArticleFilterService;
use App\Domains\News\Services\Query\Sort\ArticleSortService;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

final class ArticleController extends Controller
{
    public function index(ArticleIndexRequest $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(self::getBaseQuery());

        return $this->respondPaginated(
            LightArticleResource::class,
            $query,
            $request,
            app(ArticleFilterService::class, [
                'filters' => $request->validated(QueryKey::FILTER->value, []),
                'query' => $query->clone(),
            ]),
            app(ArticleSortService::class)
        );
    }

    public function show(string $slug): JsonResource|JsonResponse
    {
        $query = self::getBaseQuery()->where('slug', $slug);

        return $this->respondWithPossiblyNotFoundItem(HeavyArticleResource::class, $query);
    }

    private static function getBaseQuery(): ArticleBuilder
    {
        return Article::query()->published();
    }
}
