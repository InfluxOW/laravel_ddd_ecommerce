<?php

namespace App\Components\Purchasable;

use App\Components\Purchasable\Enums\Translation\PriceTranslationKey;

return [
    PriceTranslationKey::class => [
        PriceTranslationKey::CURRENCY->name => 'Currency',
        PriceTranslationKey::PRICE->name => 'Price',
        PriceTranslationKey::PRICE_DISCOUNTED->name => 'Price Discounted',
    ],
];
