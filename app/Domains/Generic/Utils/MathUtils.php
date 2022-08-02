<?php

namespace App\Domains\Generic\Utils;

use Akaunting\Money\Money;
use Carbon\Carbon;

final class MathUtils
{
    public static function clamp(Carbon|Money|int|float $value, Carbon|Money|int|float|null $min, Carbon|Money|int|float|null $max): Carbon|Money|int|float
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
