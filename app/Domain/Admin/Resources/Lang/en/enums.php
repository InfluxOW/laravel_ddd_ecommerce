<?php

use App\Domain\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domain\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domain\Admin\Enums\Translation\Components\Cards\AdminTimestampsCardTranslationKey;

return [
    AdminNavigationGroupTranslationKey::class => [
        AdminNavigationGroupTranslationKey::SHOP->value => 'Shop',
    ],
    AdminTimestampsCardTranslationKey::class => [
        AdminTimestampsCardTranslationKey::UPDATED_AT->value => 'Created At',
        AdminTimestampsCardTranslationKey::CREATED_AT->value => 'Last Modified At',
    ],
    AdminActionTranslationKey::class => [
        AdminActionTranslationKey::VIEW->value => 'View',
    ]
];
