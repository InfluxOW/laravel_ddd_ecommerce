<?php

namespace App\Components\Generic\Utils;

use BackedEnum;

class EnumUtils
{
    public static function descendingValue(BackedEnum $sort): string
    {
        return "-{$sort->value}";
    }
}
