<?php

namespace App\Domain\Generic\Utils;

use App\Domain\Generic\Enums\BooleanString;

class StringUtils
{
    public static function boolToString(bool $bool): string
    {
        return $bool ? BooleanString::TRUE->value : BooleanString::FALSE->value;
    }
}
