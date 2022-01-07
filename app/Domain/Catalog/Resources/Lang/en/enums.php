<?php

use App\Domain\Generic\Utils\EnumUtils;
use App\Domain\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domain\Catalog\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domain\Catalog\Enums\Translation\CatalogSettingsTranslationKey;

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
        ProductCategoryResourceTranslationKey::DESCRIPTION->name => 'Description',
        ProductCategoryResourceTranslationKey::PARENT_ID->name => 'Parent',
        ProductCategoryResourceTranslationKey::PARENT_TITLE->name => 'Parent',
        ProductCategoryResourceTranslationKey::LEFT->name => 'Position',
        ProductCategoryResourceTranslationKey::IS_VISIBLE->name => 'Is Visible',

        ProductCategoryResourceTranslationKey::DEPTH->name => 'Depth',
    ],
    CatalogSettingsTranslationKey::class => [
        CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->name => 'Available Currencies',
        CatalogSettingsTranslationKey::DEFAULT_CURRENCY->name => 'Default Currency',
    ],
];