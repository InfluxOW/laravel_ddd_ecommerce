<?php

namespace App\Domains\News\Services\Query\Sort;

use App\Components\Queryable\Abstracts\Sort\SortService;
use App\Domains\News\Enums\Query\Sort\ArticleAllowedSort;
use Illuminate\Database\Eloquent\Builder;

final class ArticleSortService extends SortService
{
    public function build(): static
    {
        return $this
            ->add(ArticleAllowedSort::DEFAULT, static fn (Builder $query): Builder => $query)
            ->add(ArticleAllowedSort::PUBLISHED_AT_DESC)
            ->add(ArticleAllowedSort::PUBLISHED_AT)
            ->add(ArticleAllowedSort::TITLE_DESC)
            ->add(ArticleAllowedSort::TITLE);
    }
}
