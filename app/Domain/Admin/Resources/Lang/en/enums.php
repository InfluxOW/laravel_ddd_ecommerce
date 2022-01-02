<?php

use App\Domain\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domain\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domain\Admin\Enums\Translation\Components\Cards\AdminTimestampsCardTranslationKey;

return [
    AdminNavigationGroupTranslationKey::class => [
        AdminNavigationGroupTranslationKey::GENERIC->name => 'Generic',
        AdminNavigationGroupTranslationKey::CATALOG->name => 'Catalog',
        AdminNavigationGroupTranslationKey::SETTINGS->name => 'Settings',
    ],
    AdminTimestampsCardTranslationKey::class => [
        AdminTimestampsCardTranslationKey::UPDATED_AT->name => 'Last Modified At',
        AdminTimestampsCardTranslationKey::CREATED_AT->name => 'Created At',
    ],
    AdminActionTranslationKey::class => [
        AdminActionTranslationKey::VIEW->name => 'View',
    ]
];
