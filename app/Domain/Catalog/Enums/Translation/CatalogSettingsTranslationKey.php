<?php

namespace App\Domain\Catalog\Enums\Translation;

enum CatalogSettingsTranslationKey: string
{
    case AVAILABLE_CURRENCIES = 'available_currencies';
    case DEFAULT_CURRENCY = 'default_currency';
}
