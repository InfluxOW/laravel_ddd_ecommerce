<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Components\Generic\Enums\Lang\TranslationFilename;
use App\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Components\Generic\Utils\AppUtils;
use App\Components\Generic\Utils\LangUtils;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Infrastructure\Abstracts\ServiceProviderBase;
use Closure;
use UnitEnum;

trait TranslatableAdmin
{
    protected static function translateComponentProperty(AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey|AdminPagePropertyTranslationKey|AdminWidgetPropertyTranslationKey $enum, bool $allowClosures = false): string|Closure
    {
        return static::translateAdminEnum($enum, $allowClosures);
    }

    protected static function translateAdminEnum(UnitEnum $enum, bool $allowClosures = false): string|Closure
    {
        return LangUtils::translateValue(static::getTranslationNamespace(), TranslationFilename::ADMIN, $enum->name, static::class, $allowClosures);
    }

    protected static function translateEnum(UnitEnum $enum, bool $allowClosures = false): string|Closure
    {
        return LangUtils::translateEnum(static::getTranslationNamespace(), $enum, null, $allowClosures);
    }

    protected static function getTranslationNamespace(): TranslationNamespace
    {
        /** @var ServiceProviderBase|null $domainServiceProvider */
        $domainServiceProvider = AppUtils::guessDomainServiceProvider(static::class);

        return ($domainServiceProvider === null) ? TranslationNamespace::DEFAULT : $domainServiceProvider::TRANSLATION_NAMESPACE;
    }
}
