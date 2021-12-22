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
        $translation = __(sprintf('%s::%s.%s', $namespace->value, $filename->value, $value));

        return is_string($translation) ? $translation : '';
    }

    public static function translateEnum(TranslationNamespace $namespace, BackedEnum $enum, string|int|null $value = null): string
    {
        return self::translateValue($namespace, TranslationFilename::ENUMS, $value ?? $enum->value, $enum::class);
    }
}
