<?php

use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domains\Catalog\Enums\Translation\AdminActionTranslationKey;
use App\Domains\Catalog\Enums\Translation\CatalogSettingsTranslationKey;
use App\Domains\Catalog\Enums\Translation\ProductCategoryTranslationKey;
use App\Domains\Catalog\Enums\Translation\ProductTranslationKey;

return [
    ProductAllowedFilter::class => [
        ProductAllowedFilter::SEARCH->name => 'Search',
        ProductAllowedFilter::PRICE_BETWEEN->name => 'Price',
        ProductAllowedFilter::CATEGORY->name => 'Category',
        ProductAllowedFilter::ATTRIBUTE_VALUE->name => 'Attribute',
        ProductAllowedFilter::CURRENCY->name => 'Currency',
    ],
    ProductAllowedSort::class => [
        ProductAllowedSort::DEFAULT->name => 'Default',
        ProductAllowedSort::TITLE->name => 'Title A-Z',
        ProductAllowedSort::PRICE->name => 'Cheap First',
        ProductAllowedSort::CREATED_AT->name => 'Oldest First',
        ProductAllowedSort::TITLE_DESC->name => 'Title Z-A',
        ProductAllowedSort::PRICE_DESC->name => 'Expensive First',
        ProductAllowedSort::CREATED_AT_DESC->name => 'Newest First',
    ],
    ProductCategoryTranslationKey::class => [
        ProductCategoryTranslationKey::MAIN->name => 'Main',
        ProductCategoryTranslationKey::STATISTICS->name => 'Statistics',
        ProductCategoryTranslationKey::ADDITIONAL->name => 'Additional',

        ProductCategoryTranslationKey::ID->name => 'Id',
        ProductCategoryTranslationKey::PATH->name => 'Breadcrumbs',
        ProductCategoryTranslationKey::TITLE->name => 'Title',
        ProductCategoryTranslationKey::SLUG->name => 'Slug',
        ProductCategoryTranslationKey::DESCRIPTION->name => 'Description',
        ProductCategoryTranslationKey::PARENT_ID->name => 'Parent',
        ProductCategoryTranslationKey::PARENT_TITLE->name => 'Parent',
        ProductCategoryTranslationKey::LEFT->name => 'Position',
        ProductCategoryTranslationKey::IS_VISIBLE->name => 'Is Visible',
        ProductCategoryTranslationKey::IS_DISPLAYABLE->name => 'Is Displayable',
        ProductCategoryTranslationKey::IMAGES->name => 'Images',
        ProductCategoryTranslationKey::PRODUCTS_STRING->name => 'Products',
        ProductCategoryTranslationKey::CREATED_AT->name => 'Created At',
        ProductCategoryTranslationKey::UPDATED_AT->name => 'Updated At',

        ProductCategoryTranslationKey::DEPTH->name => 'Depth',

        ProductCategoryTranslationKey::PRODUCTS_COUNT->name => 'Products Count',
        ProductCategoryTranslationKey::OVERALL_PRODUCTS_COUNT->name => 'Products Count Including Descendants',
    ],
    CatalogSettingsTranslationKey::class => [
        CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->name => 'Available Currencies',
        CatalogSettingsTranslationKey::REQUIRED_CURRENCIES->name => 'Required Currencies',
        CatalogSettingsTranslationKey::DEFAULT_CURRENCY->name => 'Default Currency',
    ],
    ProductTranslationKey::class => [
        ProductTranslationKey::ID->name => 'ID',
        ProductTranslationKey::TITLE->name => 'Title',
        ProductTranslationKey::SLUG->name => 'Slug',
        ProductTranslationKey::DESCRIPTION->name => 'Description',
        ProductTranslationKey::CATEGORIES->name => 'Categories',
        ProductTranslationKey::CATEGORIES_STRING->name => 'Categories',
        ProductTranslationKey::IMAGES->name => 'Images',
        ProductTranslationKey::PRICES_STRING->name => 'Prices',
        ProductTranslationKey::ATTRIBUTE_VALUES_STRING->name => 'Attributes',
        ProductTranslationKey::IS_VISIBLE->name => 'Is Visible',
        ProductTranslationKey::IS_DISPLAYABLE->name => 'Is Displayable',
        ProductTranslationKey::CREATED_AT->name => 'Created At',
        ProductTranslationKey::UPDATED_AT->name => 'Updated At',
    ],
    AdminActionTranslationKey::class => [
        AdminActionTranslationKey::HIERARCHY->name => 'Hierarchy',
    ],
];
