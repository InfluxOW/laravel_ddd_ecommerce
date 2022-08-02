<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ProductFilterService extends FilterService
{
    private array $validated;

    public function prepare(array $validated, SpatieQueryBuilder $query): static
    {
        $this->validated = $validated;

        $currency = $this->getFilter(ProductAllowedFilter::CURRENCY);
        /** @var ProductFilterBuilder $builder */
        $builder = $this->builder;

        $builder->prepare($currency, $query);

        return $this;
    }

    public function build(): static
    {
        $getFilter = fn (ProductAllowedFilter $filter): mixed => $this->getFilter($filter);

        return $this
            /** @phpstan-ignore-next-line */
            ->add(ProductAllowedFilter::SEARCH, static fn (Builder|Product $query): Builder => $query->search($getFilter(ProductAllowedFilter::SEARCH), orderByScore: true))
            ->add(ProductAllowedFilter::CURRENCY, static fn (Builder|Product $query): Builder => $query->whereHasPriceCurrency($getFilter(ProductAllowedFilter::CURRENCY)))
            ->add(ProductAllowedFilter::CATEGORY, static fn (Builder|Product $query): Builder => $query->whereInCategory(ProductCategory::query()->displayable()->whereIn('slug', $getFilter(ProductAllowedFilter::CATEGORY))->get()))
            ->add(ProductAllowedFilter::PRICE_BETWEEN, static fn (Builder|Product $query): Builder => $query->wherePriceBetween(...$getFilter(ProductAllowedFilter::PRICE_BETWEEN)))
            ->add(ProductAllowedFilter::ATTRIBUTE_VALUE, static fn (Builder|Product $query): Builder => $query->whereHasAttributeValue($getFilter(ProductAllowedFilter::ATTRIBUTE_VALUE)));
    }

    private function getFilter(ProductAllowedFilter $filter): mixed
    {
        return $this->validated[QueryKey::FILTER->value][$filter->name];
    }
}
