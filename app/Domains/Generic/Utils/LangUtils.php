<?php

namespace App\Domains\Generic\Utils;

use App\Domains\Generic\Enums\Lang\TranslationFilename;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use Closure;
use Illuminate\Support\Arr;
use UnitEnum;

final class LangUtils
{
    public static function translateValue(ServiceProviderNamespace $namespace, TranslationFilename $filename, string|int $value, ?string $key = null, bool $allowClosures = false): string|Closure
    {
        $value = ($key === null) ? $value : sprintf('%s.%s', $key, $value);

        /*
         * Workaround for a wierd bug that breaks running tests in coverage mode
         * with error "Target class [translator] does not exist" despite successfully passed tests
         * */
        $translation = app()->bound('translator') ? __(sprintf('%s::%s.%s', $namespace->value, $filename->value, $value)) : (string) $value;

        if (is_array($translation)) {
            $translation = Arr::first($translation);
        }

        if (is_string($translation)) {
            return $translation;
        }

        if ($translation instanceof Closure && $allowClosures) {
            return $translation;
        }

        return (string) $value;
    }

    public static function translateEnum(UnitEnum $enum, string|int|null $value = null, ?ServiceProviderNamespace $namespace = null, bool $allowClosures = false): string|Closure
    {
        return self::translateValue($namespace ?? AppUtils::guessServiceProviderNamespace($enum::class), TranslationFilename::ENUMS, $value ?? $enum->name, $enum::class, $allowClosures);
    }
}
