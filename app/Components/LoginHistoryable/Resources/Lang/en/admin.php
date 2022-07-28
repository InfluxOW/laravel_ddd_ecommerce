<?php

use App\Components\LoginHistoryable\Admin\RelationManagers\LoginHistoryRelationManager;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;

return [
    LoginHistoryRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Login History',
        AdminRelationPropertyTranslationKey::LABEL->name => 'login history',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'login history',
    ],
];
