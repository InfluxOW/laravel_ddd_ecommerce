<?php

use App\Domain\Products\Enums\Filters\ProductAllowedFilter;
use App\Domain\Products\Enums\Sorts\ProductAllowedSort;

return [
    ProductAllowedFilter::class => [
        ProductAllowedFilter::TITLE->value => 'Title',
        ProductAllowedFilter::DESCRIPTION->value => 'Description',
        ProductAllowedFilter::PRICE_BETWEEN->value => 'Price',
        ProductAllowedFilter::CATEGORY->value => 'Category',
        ProductAllowedFilter::ATTRIBUTE_VALUE->value => 'Attribute',
    ],
    ProductAllowedSort::class => [
        ProductAllowedSort::TITLE->value => 'Title A-Z',
        ProductAllowedSort::PRICE->value => 'Cheap First',
        ProductAllowedSort::CREATED_AT->value => 'Oldest First',
        ProductAllowedSort::TITLE->descendingValue() => 'Title Z-A',
        ProductAllowedSort::PRICE->descendingValue() => 'Expensive First',
        ProductAllowedSort::CREATED_AT->descendingValue() => 'Newest First',
    ],
];
