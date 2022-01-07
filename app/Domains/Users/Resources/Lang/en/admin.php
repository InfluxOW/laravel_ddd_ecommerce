<?php

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Utils\LangUtils;
use App\Domains\Users\Admin\Resources\UserResource;

return [
    UserResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Customer',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminNavigationGroupTranslationKey::GENERIC),
    ],
];
