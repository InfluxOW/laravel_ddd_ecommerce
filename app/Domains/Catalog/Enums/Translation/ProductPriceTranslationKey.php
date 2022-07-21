<?php

namespace App\Domains\Catalog\Enums\Translation;

enum ProductPriceTranslationKey: string
{
    case PRICE = 'price';
    case PRICE_DISCOUNTED = 'price_discounted';
    case CURRENCY = 'currency';
}
