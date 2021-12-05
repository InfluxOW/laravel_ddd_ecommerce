<?php

namespace App\Domain\Generic\Utils;

use BackedEnum;
use Illuminate\Support\Str;

class LangUtils
{
    public static function translateEnum(BackedEnum $enum, string|int|null $value = null): string
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

        $translation = __(sprintf('%s::enums.%s.%s', $domainProviderClass::ALIAS, $enum::class, $value ?? $enum->value));

        return is_string($translation) ? $translation : '';
    }
}
