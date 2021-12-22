<?php

namespace App\Domain\Products\Services\Query\Filter;

use App\Domain\Generic\Currency\Models\Kopecks;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Query\Models\Filter\Filter;
use App\Domain\Generic\Query\Models\Filter\InputFilter;
use App\Domain\Generic\Query\Models\Filter\MultiselectFilter;
use App\Domain\Generic\Query\Models\Filter\RangeFilter;
use App\Domain\Products\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Products\Providers\DomainServiceProvider;
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

    public function buildPriceBetweenFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        $minPrice = $this->repository->getMinPrice($productsQuery);
        $maxPrice = $this->repository->getMaxPrice($productsQuery);

        return new RangeFilter(
            ProductAllowedFilter::PRICE_BETWEEN,
            $this->namespace,
            ($minPrice === null) ? null : (new Kopecks($minPrice))->roubles(),
            ($maxPrice === null) ? null : (new Kopecks($maxPrice))->roubles(),
            true
        );
    }

    public function buildCategoryFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        return MultiselectFilter::createWithPlainValues(ProductAllowedFilter::CATEGORY, $this->namespace, $this->repository->getCategories($productsQuery));
    }

    public function buildAttributeValuesFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        return MultiselectFilter::createWithNestedValues(ProductAllowedFilter::ATTRIBUTE_VALUE, $this->namespace, $this->repository->getAttributeValues($productsQuery));
    }
}
