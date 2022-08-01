<?php

namespace App\Domains\News\Http\Controllers\Api;

use App\Components\Queryable\Enums\QueryKey;
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
        $validated = $request->validated();

        $news = $this->getBaseNewsQuery()
            ->paginate($validated[QueryKey::PER_PAGE->value], ['*'], QueryKey::PAGE->value, $validated[QueryKey::PAGE->value])
            ->appends($request->query() ?? []);

        return $this->respondWithCollection(LightArticleResource::class, $news);
    }

    public function show(string $slug): JsonResource|JsonResponse
    {
        $article = $this->getBaseNewsQuery()->where('slug', $slug)->first();

        return ($article === null) ? $this->respondNotFound() : $this->respondWithItem(HeavyArticleResource::class, $article);
    }

    private function getBaseNewsQuery(): Builder
    {
        return Article::query()->published()->orderByDesc('published_at');
    }
}
