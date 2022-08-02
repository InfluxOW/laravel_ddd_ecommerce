<?php

namespace App\Domains\Generic\Utils;

use Akaunting\Money\Money;

final class MathUtils
{
    public static function clamp(Money|int|float $value, Money|int|float|null $min, Money|int|float|null $max): Money|int|float
    {
        if (isset($max) && $value > $max) {
            $value = $max;
        }

        if (isset($min) && $value < $min) {
            $value = $min;
        }

        return $value;
    }
}
