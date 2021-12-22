<?php

namespace App\Domain\Admin\Traits\Translation;

use App\Domain\Admin\Enums\AdminTranslationKey;
use App\Domain\Generic\Lang\Enums\TranslationFilename;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Utils\LangUtils;

trait Translatable
{
    abstract protected static function getTranslationNamespace(): TranslationNamespace;

    protected static function translate(AdminTranslationKey $key): string
    {
        return LangUtils::translateValue(static::getTranslationNamespace(), TranslationFilename::ADMIN, $key->value, static::class);
    }
}
