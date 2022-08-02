<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\IAllowedFilterEnum;
use App\Components\Queryable\Abstracts\FilterBuilder;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Classes\Filter\InputFilter;
use App\Components\Queryable\Classes\Filter\Multiselect\NestedMultiselectFilter;
use App\Components\Queryable\Classes\Filter\Multiselect\PlainMultiselectFilter;
use App\Components\Queryable\Classes\Filter\RangeFilter;
use App\Components\Queryable\Classes\Filter\SelectFilter;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;
use UnitEnum;

final class ProductFilterBuilder implements FilterBuilder
{
    private SpatieQueryBuilder $query;

    private string $currency;

    public function __construct(private readonly ProductFilterBuilderRepository $repository)
    {
    }

    public function prepare(string $currency, SpatieQueryBuilder $query): static
    {
        $this->currency = $currency;
        $this->query = $query;

        return $this;
    }

    /**
     * @param ProductAllowedFilter $filter
     */
    public function build(UnitEnum & IAllowedFilterEnum $filter): Filter
    {
        return match ($filter) {
            ProductAllowedFilter::SEARCH => $this->buildSearchFilter($filter),
            ProductAllowedFilter::CATEGORY => $this->buildCategoryFilter($filter),
            ProductAllowedFilter::PRICE_BETWEEN => $this->buildPriceBetweenFilter($filter),
            ProductAllowedFilter::ATTRIBUTE_VALUE => $this->buildAttributeValueFilter($filter),
            ProductAllowedFilter::CURRENCY => $this->buildCurrencyFilter($filter),
        };
    }

    private function buildSearchFilter(UnitEnum & IAllowedFilterEnum $filter): Filter
    {
        return new InputFilter($filter);
    }

    private function buildCurrencyFilter(UnitEnum & IAllowedFilterEnum $filter): Filter
    {
        return new SelectFilter($filter, $this->repository->getAvailableCurrencies($this->query));
    }

    private function buildPriceBetweenFilter(UnitEnum & IAllowedFilterEnum $filter): Filter
    {
        $minPrice = $this->repository->getMinPrice($this->query, $this->currency);
        $maxPrice = $this->repository->getMaxPrice($this->query, $this->currency);

        return new RangeFilter(
            $filter,
            isset($minPrice) ? money($minPrice, $this->currency) : null,
            isset($maxPrice) ? money($maxPrice, $this->currency) : null,
        );
    }

    private function buildCategoryFilter(UnitEnum & IAllowedFilterEnum $filter): Filter
    {
        return new PlainMultiselectFilter($filter, $this->repository->getCategories($this->query));
    }

    private function buildAttributeValueFilter(UnitEnum & IAllowedFilterEnum $filter): Filter
    {
        return new NestedMultiselectFilter($filter, $this->repository->getAttributeValues($this->query));
    }
}
