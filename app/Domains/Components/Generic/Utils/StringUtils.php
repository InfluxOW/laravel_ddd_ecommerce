<?php

namespace App\Domains\Components\Generic\Utils;

use App\Domains\Components\Generic\Enums\BooleanString;

class StringUtils
{
    public static function boolToString(bool $bool): string
    {
        return $bool ? BooleanString::TRUE->value : BooleanString::FALSE->value;
    }
}
