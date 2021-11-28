<?php

namespace App\Utils;

use BackedEnum;
use Illuminate\Support\Str;

class LangUtils
{
    public static function translateEnum(BackedEnum $enum, string|int|null $value = null): array|string|null
    {
        /**
         * Get domain provider in the enum's domain
         *
         * @var class-string $domainProviderClass
         */
        $domainProviderClass = Str::of($enum::class)
            ->explode('\\')
            ->shift(3)
            ->push('Providers', 'DomainServiceProvider')
            ->implode('\\');

        return __(sprintf('%s::enums.%s.%s', $domainProviderClass::ALIAS, $enum::class, $value ?? $enum->value));
    }
}
