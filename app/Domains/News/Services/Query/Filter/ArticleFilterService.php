<?php

namespace App\Domains\News\Services\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Domains\News\Database\Builders\ArticleBuilder;
use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ArticleFilterService extends FilterService
{
    public function prepare(array $validated, SpatieQueryBuilder $query): static
    {
        $this->validated = $validated;

        /** @var ArticleFilterBuilder $builder */
        $builder = $this->builder;

        $builder->prepare($query);

        return $this;
    }

    public function build(): static
    {
        $getFilter = fn (ArticleAllowedFilter $filter): mixed => $this->getFilter($filter);

        return $this
            ->addSearchFilter(ArticleAllowedFilter::SEARCH, static fn (ArticleBuilder $query) => $query->search($getFilter(ArticleAllowedFilter::SEARCH), orderByScore: true))
            ->addFilter(ArticleAllowedFilter::PUBLISHED_BETWEEN, static fn (ArticleBuilder $query) => $query->wherePublishedBetween(...$getFilter(ArticleAllowedFilter::PUBLISHED_BETWEEN)));
    }
}
