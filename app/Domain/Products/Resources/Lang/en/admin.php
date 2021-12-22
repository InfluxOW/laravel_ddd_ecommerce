<?php

use App\Domain\Admin\Enums\AdminTranslationKey;
use App\Domain\Admin\Enums\NavigationGroup;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Generic\Utils\LangUtils;
use App\Domain\Products\Admin\Resources\ProductCategoryResource;

return [
    ProductCategoryResource::class => [
        AdminTranslationKey::LABEL->value => 'Category',
        AdminTranslationKey::PLURAL_LABEL->value => 'Categories',
        AdminTranslationKey::NAVIGATION_LABEL->value => 'Categories',
        AdminTranslationKey::NAVIGATION_GROUP->value => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, NavigationGroup::SHOP),
    ],
];
