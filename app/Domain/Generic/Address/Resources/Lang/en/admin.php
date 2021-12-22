<?php

use App\Domain\Admin\Enums\AdminTranslationKey;
use App\Domain\Generic\Address\Admin\RelationManagers\AddressesRelationManager;

return [
    AddressesRelationManager::class => [
        AdminTranslationKey::TITLE->value => 'Addresses',
        AdminTranslationKey::LABEL->value => 'address',
        AdminTranslationKey::PLURAL_LABEL->value => 'addresses',
    ],
];
