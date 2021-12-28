<?php

use App\Domain\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domain\Generic\Address\Admin\RelationManagers\AddressesRelationManager;

return [
    AddressesRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Addresses',
        AdminRelationPropertyTranslationKey::LABEL->name => 'address',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'addresses',
    ],
];
