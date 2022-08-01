<?php

namespace App\Domains\News\Http\Resources\Article;

use App\Components\Mediable\Http\Resources\MediaResource;
use App\Domains\News\Models\Article;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JetBrains\PhpStorm\ArrayShape;
use Stevebauman\Purify\Facades\Purify;

final class HeavyArticleResource extends LightArticleResource
{
    #[ArrayShape(['slug' => 'string', 'title' => 'string', 'url' => 'string', 'image' => MediaResource::class, 'description' => 'string', 'published_at' => 'string|null', 'images' => AnonymousResourceCollection::class, 'body' => 'string'])]
    public function toArray($request): array
    {
        /** @var Article $article */
        $article = $this->resource;

        return array_merge(parent::toArray($request), [
            'images' => MediaResource::collection($article->images),
            'body' => Purify::clean($article->body),
        ]);
    }
}
