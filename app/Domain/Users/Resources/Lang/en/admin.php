<?php

use App\Domain\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domain\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Generic\Utils\LangUtils;
use App\Domain\Users\Admin\Resources\UserResource;

return [
    UserResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Customer',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::SHOP),
    ],
];
