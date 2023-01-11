<?php

namespace App\Domains\Catalog;

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey as BaseAdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Catalog\Admin\Pages\ManageCatalogSettings;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers\ProductCategoryChildrenRelationManager;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use App\Domains\Catalog\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Common\Utils\LangUtils;

return [
    ProductCategoryResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Category',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::CATALOG),
    ],
    ProductResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Product',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Products',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Products',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::CATALOG),
    ],
    ProductCategoryChildrenRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Children',
        AdminRelationPropertyTranslationKey::LABEL->name => 'child',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'children',
    ],
    ManageCatalogSettings::class => [
        AdminPagePropertyTranslationKey::TITLE->name => 'Catalog Settings',
        AdminPagePropertyTranslationKey::NAVIGATION_LABEL->name => 'Catalog',
        AdminPagePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(BaseAdminNavigationGroupTranslationKey::SETTINGS),
    ],
];
