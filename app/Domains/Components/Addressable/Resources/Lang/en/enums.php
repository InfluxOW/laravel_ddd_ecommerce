<?php

use App\Domains\Components\Addressable\Enums\Translation\AddressesRelationManagerTranslationKey;

return [
    AddressesRelationManagerTranslationKey::class => [
        AddressesRelationManagerTranslationKey::ZIP->name => 'Zip / Postal Code',
        AddressesRelationManagerTranslationKey::REGION->name => 'Country',
        AddressesRelationManagerTranslationKey::COUNTRY->name => 'Region',
        AddressesRelationManagerTranslationKey::CITY->name => 'City',
        AddressesRelationManagerTranslationKey::STREET->name => 'Street Address',
    ],
];
