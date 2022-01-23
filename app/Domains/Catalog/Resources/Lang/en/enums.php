<?php

use App\Components\Generic\Utils\EnumUtils;
use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domains\Catalog\Enums\Translation\CatalogSettingsTranslationKey;
use App\Domains\Catalog\Enums\Translation\ProductAttributeResourceTranslationKey;
use App\Domains\Catalog\Enums\Translation\ProductAttributeValueResourceTranslationKey;
use App\Domains\Catalog\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domains\Catalog\Enums\Translation\ProductPriceResourceTranslationKey;
use App\Domains\Catalog\Enums\Translation\ProductResourceTranslationKey;

return [
    ProductAllowedFilter::class => [
        ProductAllowedFilter::TITLE->name => 'Title',
        ProductAllowedFilter::DESCRIPTION->name => 'Description',
        ProductAllowedFilter::PRICE_BETWEEN->name => 'Price',
        ProductAllowedFilter::CATEGORY->name => 'Category',
        ProductAllowedFilter::ATTRIBUTE_VALUE->name => 'Attribute',
        ProductAllowedFilter::CURRENCY->name => 'Currency',
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
    ProductAttributeValueResourceTranslationKey::class => [
        ProductAttributeValueResourceTranslationKey::ATTRIBUTE_TITLE->name => 'Attribute',
        ProductAttributeValueResourceTranslationKey::ATTRIBUTE->name => 'Attribute',
        ProductAttributeValueResourceTranslationKey::READABLE_VALUE->name => 'Value',
        ProductAttributeValueResourceTranslationKey::VALUE_STRING->name => 'Value',
        ProductAttributeValueResourceTranslationKey::VALUE_BOOLEAN->name => 'Value',
        ProductAttributeValueResourceTranslationKey::VALUE_FLOAT->name => 'Value',
        ProductAttributeValueResourceTranslationKey::VALUE_INTEGER->name => 'Value',
    ],
    ProductAttributeResourceTranslationKey::class => [
        ProductAttributeResourceTranslationKey::TITLE->name => 'Title',
        ProductAttributeResourceTranslationKey::SLUG->name => 'Slug',
        ProductAttributeResourceTranslationKey::VALUES_TYPE->name => 'Type Of Values',
    ],
    CatalogSettingsTranslationKey::class => [
        CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->name => 'Available Currencies',
        CatalogSettingsTranslationKey::DEFAULT_CURRENCY->name => 'Default Currency',
    ],
    ProductAttributeValuesType::class => [
        ProductAttributeValuesType::STRING->name => 'String',
        ProductAttributeValuesType::BOOLEAN->name => 'Boolean',
        ProductAttributeValuesType::FLOAT->name => 'Float',
        ProductAttributeValuesType::INTEGER->name => 'Integer',
    ],
    ProductResourceTranslationKey::class => [
        ProductResourceTranslationKey::TITLE->name => 'Title',
        ProductResourceTranslationKey::SLUG->name => 'Slug',
        ProductResourceTranslationKey::DESCRIPTION->name => 'Description',
    ],
    ProductPriceResourceTranslationKey::class => [
        ProductPriceResourceTranslationKey::CURRENCY->name => 'Currency',
        ProductPriceResourceTranslationKey::PRICE->name => 'Price',
        ProductPriceResourceTranslationKey::PRICE_DISCOUNTED->name => 'Price Discounted',
    ],
];
