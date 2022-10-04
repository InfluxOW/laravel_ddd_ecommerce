<?php

namespace App\Domains\News\Services\Query\Sort;

use App\Components\Queryable\Abstracts\Sort\SortService;
use App\Domains\News\Database\Builders\ArticleBuilder;
use App\Domains\News\Enums\Query\Sort\ArticleAllowedSort;

final class ArticleSortService extends SortService
{
    public function build(): static
    {
        return $this
            ->addDefaultSort(ArticleAllowedSort::PUBLISHED_AT_DESC)
            ->addSort(ArticleAllowedSort::PUBLISHED_AT)
            ->addSort(ArticleAllowedSort::TITLE_DESC)
            ->addSort(ArticleAllowedSort::TITLE)
            ->addDefaultSearchSort(ArticleAllowedSort::DEFAULT, static fn (ArticleBuilder $query) => $query);
    }
}
