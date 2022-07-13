<?php

namespace App\Domains\Catalog\Enums\Translation;

enum CatalogSettingsTranslationKey: string
{
    case AVAILABLE_CURRENCIES = 'available_currencies';
    case REQUIRED_CURRENCIES = 'required_currencies';
    case DEFAULT_CURRENCY = 'default_currency';
}
