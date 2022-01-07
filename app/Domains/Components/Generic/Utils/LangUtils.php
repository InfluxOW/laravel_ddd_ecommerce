<?php

namespace App\Domains\Components\Generic\Utils;

use App\Domains\Components\Generic\Enums\Lang\TranslationFilename;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use UnitEnum;

use function __;
use function app;

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

    public static function translateEnum(TranslationNamespace $namespace, UnitEnum $enum, string|int|null $value = null): string
    {
        return self::translateValue($namespace, TranslationFilename::ENUMS, $value ?? $enum->name, $enum::class);
    }
}
