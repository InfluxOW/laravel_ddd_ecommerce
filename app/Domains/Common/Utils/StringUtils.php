<?php

namespace App\Domains\Common\Utils;

use App\Domains\Common\Enums\BooleanString;
use Illuminate\Support\Str;

final class StringUtils
{
    public static function boolToString(bool $bool): string
    {
        return $bool ? BooleanString::_TRUE->value : BooleanString::_FALSE->value;
    }

    public static function pluralBasename(string $class): string
    {
        /** @var string $basename */
        $basename = Str::of($class)->explode('\\')->last();

        return (string) Str::of($basename)->snake()->plural()->lower();
    }
}
