<?php

namespace App\Domains\News\Http\Controllers\Api;

use App\Domains\News\Http\Requests\ArticleIndexRequest;
use App\Domains\News\Http\Resources\Article\HeavyArticleResource;
use App\Domains\News\Http\Resources\Article\LightArticleResource;
use App\Domains\News\Models\Article;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

final class ArticleController extends Controller
{
    public function index(ArticleIndexRequest $request): AnonymousResourceCollection
    {
        return $this->respondPaginated(LightArticleResource::class, self::getBaseNewsQuery(), $request);
    }

    public function show(string $slug): JsonResource|JsonResponse
    {
        $query = self::getBaseNewsQuery()->where('slug', $slug);

        return $this->respondWithPossiblyNotFoundItem(HeavyArticleResource::class, $query);
    }

    private static function getBaseNewsQuery(): Builder
    {
        return Article::query()->published()->orderByDesc('published_at');
    }
}
