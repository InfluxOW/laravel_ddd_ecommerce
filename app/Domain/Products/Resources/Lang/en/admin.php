<?php

use App\Domain\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domain\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domain\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Generic\Utils\LangUtils;
use App\Domain\Products\Admin\Resources\ProductCategoryResource;

return [
    ProductCategoryResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->value => 'Category',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->value => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->value => 'Categories',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->value => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::SHOP),
    ],
    ProductCategoryResource\RelationManagers\ProductCategoryChildrenRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->value => 'Children',
        AdminRelationPropertyTranslationKey::LABEL->value => 'child',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->value => 'children',
    ],
];
