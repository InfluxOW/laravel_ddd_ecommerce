<?php

namespace App\Domains\Generic\Utils;

use App\Domains\Generic\Enums\BooleanString;
use Illuminate\Support\Str;

final class StringUtils
{
    public static function boolToString(bool $bool): string
    {
        return $bool ? BooleanString::_TRUE->value : BooleanString::_FALSE->value;
    }

    public static function pluralBasename(string $class): string
    {
        return (string) Str::of(Str::of($class)->explode('\\')->last())->snake()->plural()->lower();
    }
}
