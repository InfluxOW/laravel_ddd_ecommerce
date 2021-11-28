<?php

use App\Domain\Products\Enums\ProductAllowedFilter;
use App\Domain\Products\Enums\ProductAllowedSort;

return [
    ProductAllowedFilter::class => [
        ProductAllowedFilter::TITLE->value => 'Title',
        ProductAllowedFilter::DESCRIPTION->value => 'Description',
        ProductAllowedFilter::PRICE_BETWEEN->value => 'Price',
        ProductAllowedFilter::CATEGORY->value => 'Category',
        ProductAllowedFilter::ATTRIBUTE->value => 'Attribute',
    ],
    ProductAllowedSort::class => [
        ProductAllowedSort::TITLE->value => 'Title A-Z',
        ProductAllowedSort::PRICE->value => 'Cheap First',
        ProductAllowedSort::CREATED_AT->value => 'Oldest First',
        '-' . ProductAllowedSort::TITLE->value => 'Title Z-A',
        '-' . ProductAllowedSort::PRICE->value => 'Expensive First',
        '-' . ProductAllowedSort::CREATED_AT->value => 'Newest First',
    ],
];
