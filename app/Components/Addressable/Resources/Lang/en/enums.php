<?php

namespace App\Components\Addressable;

use App\Components\Addressable\Enums\Translation\AddressesTranslationKey;

return [
    AddressesTranslationKey::class => [
        AddressesTranslationKey::ZIP->name => 'Zip / Postal Code',
        AddressesTranslationKey::REGION->name => 'Region',
        AddressesTranslationKey::COUNTRY->name => 'Country',
        AddressesTranslationKey::CITY->name => 'City',
        AddressesTranslationKey::STREET->name => 'Street Address',
    ],
];
