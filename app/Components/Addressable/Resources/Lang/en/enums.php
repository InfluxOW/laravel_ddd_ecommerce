<?php

use App\Components\Addressable\Enums\Translation\AddressesRelationManagerTranslationKey;

return [
    AddressesRelationManagerTranslationKey::class => [
        AddressesRelationManagerTranslationKey::ZIP->name => 'Zip / Postal Code',
        AddressesRelationManagerTranslationKey::REGION->name => 'Region',
        AddressesRelationManagerTranslationKey::COUNTRY->name => 'Country',
        AddressesRelationManagerTranslationKey::CITY->name => 'City',
        AddressesRelationManagerTranslationKey::STREET->name => 'Street Address',
    ],
];
