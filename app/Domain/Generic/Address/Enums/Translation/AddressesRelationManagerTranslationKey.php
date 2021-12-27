<?php

namespace App\Domain\Generic\Address\Enums\Translation;

enum AddressesRelationManagerTranslationKey: string
{
    case ZIP = 'zip';
    case COUNTRY = 'country';
    case REGION = 'region';
    case CITY = 'city';
    case STREET = 'street';
}
