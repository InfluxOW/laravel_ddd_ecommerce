<?php

namespace App\Domains\News\Services\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Domains\News\Database\Builders\ArticleBuilder;
use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ArticleFilterService extends FilterService
{
    public function __construct(array $filters, SpatieQueryBuilder $query)
    {
        $builder = app(ArticleFilterBuilder::class, ['query' => $query]);

        parent::__construct($filters, $builder);
    }

    public function build(): static
    {
        $getFilterValue = fn (ArticleAllowedFilter $filter): mixed => $this->getFilterValue($filter);

        return $this
            ->addSearchFilter(ArticleAllowedFilter::SEARCH, static fn (ArticleBuilder $query) => $query->search($getFilterValue(ArticleAllowedFilter::SEARCH), orderByScore: true))
            ->addFilter(ArticleAllowedFilter::PUBLISHED_BETWEEN, static fn (ArticleBuilder $query) => $query->wherePublishedBetween(...$getFilterValue(ArticleAllowedFilter::PUBLISHED_BETWEEN)));
    }
}
