<?php

namespace App\Components\Purchasable\Enums\Translation;

enum PriceTranslationKey: string
{
    case PRICE = 'price';
    case PRICE_DISCOUNTED = 'price_discounted';
    case CURRENCY = 'currency';
}
