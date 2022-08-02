<?php

namespace App\Domains\News\Services\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use App\Domains\News\Models\Article;
use Illuminate\Database\Eloquent\Builder;
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
            /** @phpstan-ignore-next-line */
            ->add(ArticleAllowedFilter::SEARCH, static fn (Builder|Article $query): Builder => $query->search($getFilter(ArticleAllowedFilter::SEARCH), orderByScore: true))
            ->add(ArticleAllowedFilter::PUBLISHED_AT, static fn (Builder|Article $query): Builder => $query->wherePublishedBetween(...$getFilter(ArticleAllowedFilter::PUBLISHED_AT)));
    }
}
