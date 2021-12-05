<?php

namespace App\Domain\Generic\Utils;

use BackedEnum;

class EnumUtils
{
    public static function descendingValue(BackedEnum $sort): string
    {
        return "-{$sort->value}";
    }
}
