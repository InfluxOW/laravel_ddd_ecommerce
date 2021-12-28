<?php

use App\Domain\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domain\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domain\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Generic\Utils\LangUtils;
use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use App\Domain\Products\Admin\Resources\ProductCategoryResource\RelationManagers\ProductCategoryChildrenRelationManager;

return [
    ProductCategoryResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Category',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::SHOP),
    ],
    ProductCategoryChildrenRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Children',
        AdminRelationPropertyTranslationKey::LABEL->name => 'child',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'children',
    ],
];
