<?php

namespace App\Components\Generic\Utils;

use BackedEnum;

final class EnumUtils
{
    public static function descendingValue(BackedEnum $sort): string
    {
        return "-{$sort->value}";
    }
}
