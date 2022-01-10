<?php

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\AdminDatasetTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\Cards\AdminTimestampsCardTranslationKey;

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
        AdminActionTranslationKey::DELETE->name => 'Delete',
    ],
    AdminDatasetTranslationKey::class => [
        AdminDatasetTranslationKey::CUSTOMERS->name => 'Customers',
    ],
];
