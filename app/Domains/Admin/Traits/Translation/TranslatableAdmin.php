<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Domains\Components\Generic\Enums\Lang\TranslationFilename;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Components\Generic\Utils\LangUtils;
use UnitEnum;

trait TranslatableAdmin
{
    protected static function translateComponentProperty(AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey|AdminPagePropertyTranslationKey|AdminWidgetPropertyTranslationKey $enum): string
    {
        return LangUtils::translateValue(static::getTranslationNamespace(), TranslationFilename::ADMIN, $enum->name, static::class);
    }

    protected static function translateEnum(UnitEnum $enum): string
    {
        return LangUtils::translateEnum(static::getTranslationNamespace(), $enum);
    }

    abstract protected static function getTranslationNamespace(): TranslationNamespace;
}
