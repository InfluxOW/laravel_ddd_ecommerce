<?php

use App\Domain\Generic\Address\Enums\Translation\AddressesRelationManagerTranslationKey;

return [
    AddressesRelationManagerTranslationKey::class => [
        AddressesRelationManagerTranslationKey::ZIP->name => 'Zip / Postal Code',
        AddressesRelationManagerTranslationKey::REGION->name => 'Country',
        AddressesRelationManagerTranslationKey::COUNTRY->name => 'Region',
        AddressesRelationManagerTranslationKey::CITY->name => 'City',
        AddressesRelationManagerTranslationKey::STREET->name => 'Street Address',
    ],
];