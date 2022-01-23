<?php

namespace App\Components\Generic\Utils;

use App\Components\Generic\Enums\BooleanString;

class StringUtils
{
    public static function boolToString(bool $bool): string
    {
        return $bool ? BooleanString::TRUE->value : BooleanString::FALSE->value;
    }
}
