<?php

namespace App\Domain\Products\Enums\Translation;

enum ProductSettingsTranslationKey: string
{
    case AVAILABLE_CURRENCIES = 'available_currencies';
    case DEFAULT_CURRENCY = 'default_currency';
}
