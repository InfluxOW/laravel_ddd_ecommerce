<?php

namespace App\Domain\Generic\Utils;

use App\Domain\Generic\Lang\Enums\TranslationFilename;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use BackedEnum;

class LangUtils
{
    public static function translateValue(TranslationNamespace $namespace, TranslationFilename $filename, string|int $value, ?string $key = null): string
    {
        $value = ($key === null) ? $value : sprintf('%s.%s', $key, $value);
        /*
         * Workaround for a wierd bug that breaks running tests in coverage mode
         * with error "Target class [translator] does not exist" despite successfully passed tests
         * */
        $translation = app()->bound('translator') ? __(sprintf('%s::%s.%s', $namespace->value, $filename->value, $value)) : (string) $value;

        return is_string($translation) ? $translation : '';
    }

    public static function translateEnum(TranslationNamespace $namespace, BackedEnum $enum, string|int|null $value = null): string
    {
        return self::translateValue($namespace, TranslationFilename::ENUMS, $value ?? $enum->value, $enum::class);
    }
}
