<?php

use App\Domain\Admin\Enums\AdminTranslationKey;
use App\Domain\Admin\Enums\NavigationGroup;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Generic\Utils\LangUtils;
use App\Domain\Users\Admin\Resources\UserResource;

return [
    UserResource::class => [
        AdminTranslationKey::LABEL->value => 'Customer',
        AdminTranslationKey::PLURAL_LABEL->value => 'Customers',
        AdminTranslationKey::NAVIGATION_LABEL->value => 'Customers',
        AdminTranslationKey::NAVIGATION_GROUP->value => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, NavigationGroup::SHOP),
    ],
];
