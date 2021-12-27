<?php

use App\Domain\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domain\Generic\Address\Admin\RelationManagers\AddressesRelationManager;

return [
    AddressesRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->value => 'Addresses',
        AdminRelationPropertyTranslationKey::LABEL->value => 'address',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->value => 'addresses',
    ],
];
