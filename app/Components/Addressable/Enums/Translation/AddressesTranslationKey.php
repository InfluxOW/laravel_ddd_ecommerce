<?php

namespace App\Components\Addressable\Enums\Translation;

enum AddressesTranslationKey: string
{
    case ZIP = 'zip';
    case COUNTRY = 'country';
    case REGION = 'region';
    case CITY = 'city';
    case STREET = 'street';
}
