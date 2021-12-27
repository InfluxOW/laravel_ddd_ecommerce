<?php

use App\Domain\Generic\Address\Enums\Translation\AddressesRelationManagerTranslationKey;

return [
    AddressesRelationManagerTranslationKey::class => [
        AddressesRelationManagerTranslationKey::ZIP->value => 'Zip / Postal Code',
        AddressesRelationManagerTranslationKey::REGION->value => 'Country',
        AddressesRelationManagerTranslationKey::COUNTRY->value => 'Region',
        AddressesRelationManagerTranslationKey::CITY->value => 'City',
        AddressesRelationManagerTranslationKey::STREET->value => 'Street Address',
    ],
];