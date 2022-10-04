<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Domains\Catalog\Database\Builders\ProductBuilder;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\ProductCategory;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ProductFilterService extends FilterService
{
    public function __construct(array $filters, SpatieQueryBuilder $query)
    {
        $builder = app(ProductFilterBuilder::class, ['currency' => $this->getFilterValue(ProductAllowedFilter::CURRENCY, $filters), 'query' => $query]);

        parent::__construct($filters, $builder);
    }

    public function build(): static
    {
        $getFilterValue = fn (ProductAllowedFilter $filter): mixed => $this->getFilterValue($filter);

        return $this
            ->addSearchFilter(ProductAllowedFilter::SEARCH, fn (ProductBuilder $query) => $query->search($getFilterValue(ProductAllowedFilter::SEARCH), orderByScore: true))
            ->addFilter(ProductAllowedFilter::CURRENCY, fn (ProductBuilder $query) => $query->whereHasPriceCurrency($getFilterValue(ProductAllowedFilter::CURRENCY)))
            ->addFilter(ProductAllowedFilter::CATEGORY, fn (ProductBuilder $query) => $query->whereInCategory(ProductCategory::query()->displayable()->whereIn('slug', $getFilterValue(ProductAllowedFilter::CATEGORY))->get()))
            ->addFilter(ProductAllowedFilter::PRICE_BETWEEN, fn (ProductBuilder $query) => $query->wherePriceBetween(...$getFilterValue(ProductAllowedFilter::PRICE_BETWEEN)))
            ->addFilter(ProductAllowedFilter::ATTRIBUTE_VALUE, fn (ProductBuilder $query) => $query->whereHasAttributeValue($getFilterValue(ProductAllowedFilter::ATTRIBUTE_VALUE)));
    }
}
