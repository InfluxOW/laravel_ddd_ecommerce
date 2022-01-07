<?php

use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Components\Addressable\Admin\RelationManagers\AddressesRelationManager;

return [
    AddressesRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Addresses',
        AdminRelationPropertyTranslationKey::LABEL->name => 'address',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'addresses',
    ],
];
