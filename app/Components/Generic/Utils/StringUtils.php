<?php

namespace App\Components\Generic\Utils;

use App\Components\Generic\Enums\BooleanString;
use Illuminate\Support\Str;

final class StringUtils
{
    public static function boolToString(bool $bool): string
    {
        return $bool ? BooleanString::TRUE->value : BooleanString::FALSE->value;
    }

    public static function pluralBasename(string $class): string
    {
        return (string) Str::of(Str::of($class)->explode('\\')->last())->snake()->plural()->lower();
    }
}
