<?php

use App\Domain\Generic\Utils\EnumUtils;
use App\Domain\Products\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Products\Enums\Query\Sort\ProductAllowedSort;
use App\Domain\Products\Enums\Translation\ProductCategoryResourceTranslationKey;

return [
    ProductAllowedFilter::class => [
        ProductAllowedFilter::TITLE->name => 'Title',
        ProductAllowedFilter::DESCRIPTION->name => 'Description',
        ProductAllowedFilter::PRICE_BETWEEN->name => 'Price',
        ProductAllowedFilter::CATEGORY->name => 'Category',
        ProductAllowedFilter::ATTRIBUTE_VALUE->name => 'Attribute',
    ],
    ProductAllowedSort::class => [
        ProductAllowedSort::TITLE->name => 'Title A-Z',
        ProductAllowedSort::PRICE->name => 'Cheap First',
        ProductAllowedSort::CREATED_AT->name => 'Oldest First',
        EnumUtils::descendingValue(ProductAllowedSort::TITLE) => 'Title Z-A',
        EnumUtils::descendingValue(ProductAllowedSort::PRICE) => 'Expensive First',
        EnumUtils::descendingValue(ProductAllowedSort::CREATED_AT) => 'Newest First',
    ],
    ProductCategoryResourceTranslationKey::class => [
        ProductCategoryResourceTranslationKey::MAIN->name => 'Main',
        ProductCategoryResourceTranslationKey::STATISTICS->name => 'Statistics',

        ProductCategoryResourceTranslationKey::PATH->name => 'Breadcrumbs',
        ProductCategoryResourceTranslationKey::TITLE->name => 'Title',
        ProductCategoryResourceTranslationKey::SLUG->name => 'Slug',
        ProductCategoryResourceTranslationKey::PARENT_ID->name => 'Parent',
        ProductCategoryResourceTranslationKey::PARENT_TITLE->name => 'Parent',
        ProductCategoryResourceTranslationKey::LEFT->name => 'Position',

        ProductCategoryResourceTranslationKey::DEPTH->name => 'Depth',
    ],
];
