<?php

namespace App\Domain\Products\Services\Filters;

use App\Domain\Products\Enums\Filters\ProductAllowedFilter;
use App\Domain\Products\Models\Generic\Filters\Filter;
use App\Domain\Products\Models\Generic\Filters\InputFilter;
use App\Domain\Products\Models\Generic\Filters\MultiselectFilter;
use App\Domain\Products\Models\Generic\Filters\RangeFilter;
use App\Domain\Products\Models\Generic\Kopecks;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class ProductFilterBuilder
{
    public function __construct(private ProductFilterBuilderRepository $repository)
    {
    }

    public function buildTitleFilter(): Filter
    {
        return new InputFilter(ProductAllowedFilter::TITLE);
    }

    public function buildDescriptionFilter(): Filter
    {
        return new InputFilter(ProductAllowedFilter::DESCRIPTION);
    }

    public function buildPriceBetweenFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        $minPrice = $this->repository->getMinPrice($productsQuery);
        $maxPrice = $this->repository->getMaxPrice($productsQuery);

        return new RangeFilter(
            ProductAllowedFilter::PRICE_BETWEEN,
            ($minPrice === null) ? null : (new Kopecks($minPrice))->roubles(),
            ($maxPrice === null) ? null : (new Kopecks($maxPrice))->roubles(),
            true
        );
    }

    public function buildCategoryFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        return MultiselectFilter::createWithPlainValues(ProductAllowedFilter::CATEGORY, $this->repository->getCategories($productsQuery));
    }

    public function buildAttributeValuesFilter(SpatieQueryBuilder $productsQuery): Filter
    {
        return MultiselectFilter::createWithNestedValues(ProductAllowedFilter::ATTRIBUTE_VALUE, $this->repository->getAttributeValues($productsQuery));
    }
}
