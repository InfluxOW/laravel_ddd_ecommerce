<?php

use App\Domain\Generic\Utils\EnumUtils;
use App\Domain\Products\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Products\Enums\Query\Sort\ProductAllowedSort;

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
        EnumUtils::descendingValue(ProductAllowedSort::TITLE) => 'Title Z-A',
        EnumUtils::descendingValue(ProductAllowedSort::PRICE) => 'Expensive First',
        EnumUtils::descendingValue(ProductAllowedSort::CREATED_AT) => 'Newest First',
    ],
];
