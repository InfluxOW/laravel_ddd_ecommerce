<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Classes\Filter\InputFilter;
use App\Components\Queryable\Classes\Filter\MultiselectFilter;
use App\Components\Queryable\Classes\Filter\RangeFilter;
use App\Components\Queryable\Classes\Filter\SelectFilter;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class ProductFilterBuilder
{
    private readonly TranslationNamespace $namespace;

    public function __construct(private ProductFilterBuilderRepository $repository)
    {
        $this->namespace = DomainServiceProvider::TRANSLATION_NAMESPACE;
    }

    public function buildTitleFilter(): Filter
    {
        return new InputFilter(ProductAllowedFilter::TITLE, $this->namespace);
    }

    public function buildDescriptionFilter(): Filter
    {
        return new InputFilter(ProductAllowedFilter::DESCRIPTION, $this->namespace);
    }

    public function buildCurrencyFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        return new SelectFilter(ProductAllowedFilter::CURRENCY, $this->namespace, $this->repository->getAvailableCurrencies($productsQuery->clone()));
    }

    public function buildPriceBetweenFilter(SpatieQueryBuilder $productsQuery, string $currency): Filter
    {
        $minPrice = $this->repository->getMinPrice($productsQuery->clone(), $currency);
        $maxPrice = $this->repository->getMaxPrice($productsQuery->clone(), $currency);

        return new RangeFilter(
            ProductAllowedFilter::PRICE_BETWEEN,
            $this->namespace,
            $minPrice,
            $maxPrice,
            $currency
        );
    }

    public function buildCategoryFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        return MultiselectFilter::createWithPlainValues(ProductAllowedFilter::CATEGORY, $this->namespace, $this->repository->getCategories($productsQuery->clone()));
    }

    public function buildAttributeValuesFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        return MultiselectFilter::createWithNestedValues(ProductAllowedFilter::ATTRIBUTE_VALUE, $this->namespace, $this->repository->getAttributeValues($productsQuery->clone()));
    }
}
