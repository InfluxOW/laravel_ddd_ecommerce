<?php

namespace App\Components\Generic\Utils;

final class MathUtils
{
    public static function clamp(int|float $value, int|float $min, int|float $max): int|float
    {
        if ($value > $max) {
            $value = $max;
        }

        if ($value < $min) {
            $value = $min;
        }

        return $value;
    }
}
