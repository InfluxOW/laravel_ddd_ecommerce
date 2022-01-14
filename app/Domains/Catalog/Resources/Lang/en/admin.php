<?php

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Providers\DomainServiceProvider;
use App\Domains\Catalog\Admin\Pages\ManageCatalogSettings;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers\ProductCategoryChildrenRelationManager;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use App\Domains\Components\Generic\Utils\LangUtils;

return [
    ProductCategoryResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Category',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::CATALOG),
    ],
    ProductAttributeResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Attribute',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Attributes',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Attributes',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::CATALOG),
    ],
    ProductResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Product',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Products',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Products',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::CATALOG),
    ],
    ProductCategoryChildrenRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Children',
        AdminRelationPropertyTranslationKey::LABEL->name => 'child',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'children',
    ],
    ManageCatalogSettings::class => [
        AdminPagePropertyTranslationKey::TITLE->name => 'Catalog Settings',
        AdminPagePropertyTranslationKey::NAVIGATION_LABEL->name => 'Catalog',
        AdminPagePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::SETTINGS),
    ],
];