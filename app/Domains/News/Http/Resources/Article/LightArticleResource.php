<?php

namespace App\Domains\News\Http\Resources\Article;

use App\Components\Mediable\Http\Resources\MediaResource;
use App\Domains\News\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class LightArticleResource extends JsonResource
{
    #[ArrayShape(['slug' => 'string', 'title' => 'string', 'url' => 'string', 'image' => MediaResource::class, 'description' => 'string', 'published_at' => 'string|null'])]
    public function toArray($request): array
    {
        /** @var Article $article */
        $article = $this->resource;

        return [
            'slug' => $article->slug,
            'title' => $article->title,
            'url' => route('news.show', $article),
            'image' => MediaResource::make($article->image),
            'description' => $article->description,
            'published_at' => $article->published_at?->defaultFormat(),
        ];
    }
}
