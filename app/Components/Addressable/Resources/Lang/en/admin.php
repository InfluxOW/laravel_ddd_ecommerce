<?php

use App\Components\Addressable\Admin\RelationManagers\AddressesRelationManager;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;

return [
    AddressesRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Addresses',
        AdminRelationPropertyTranslationKey::LABEL->name => 'address',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'addresses',
    ],
];
