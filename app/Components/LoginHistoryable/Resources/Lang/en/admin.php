<?php

use App\Components\LoginHistoryable\Admin\RelationManagers\UserLoginHistoryRelationManager;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;

return [
    UserLoginHistoryRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Login History',
        AdminRelationPropertyTranslationKey::LABEL->name => 'login history',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'login history',
    ],
];
