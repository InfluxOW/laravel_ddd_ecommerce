<?php

namespace App\Domains\News\Services\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\IAllowedFilterEnum;
use App\Components\Queryable\Abstracts\FilterBuilder;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Classes\Filter\InputFilter;
use App\Components\Queryable\Classes\Filter\RangeFilter;
use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;
use UnitEnum;

final class ArticleFilterBuilder implements FilterBuilder
{
    public function __construct(private readonly SpatieQueryBuilder $query, private readonly ArticleFilterBuilderRepository $repository)
    {
    }

    /**
     * @param ArticleAllowedFilter $filter
     */
    public function build(UnitEnum&IAllowedFilterEnum $filter): Filter
    {
        return match ($filter) {
            ArticleAllowedFilter::SEARCH => $this->buildSearchFilter($filter),
            ArticleAllowedFilter::PUBLISHED_BETWEEN => $this->buildPublishedAtFilter($filter),
        };
    }

    private function buildSearchFilter(UnitEnum&IAllowedFilterEnum $filter): Filter
    {
        return new InputFilter($filter);
    }

    private function buildPublishedAtFilter(UnitEnum&IAllowedFilterEnum $filter): Filter
    {
        return new RangeFilter(
            $filter,
            $this->repository->getMinPublishedAt($this->query),
            $this->repository->getMaxPublishedAt($this->query),
        );
    }
}
