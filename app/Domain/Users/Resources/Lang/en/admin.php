<?php

use App\Domain\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domain\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Generic\Utils\LangUtils;
use App\Domain\Users\Admin\Resources\UserResource;

return [
    UserResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->value => 'Customer',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->value => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->value => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->value => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::SHOP),
    ],
];
