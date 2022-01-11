<?php

namespace App\Domains\Components\Purchasable\Abstracts;

use Akaunting\Money\Money;

interface Purchasable
{
    public function getPurchasableData(): array;

    public function getPurchasablePrice(string $currency): Money;

    public function getPurchasablePriceDiscounted(string $currency): ?Money;
}
