<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Components\Generic\Enums\Lang\TranslationFilename;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Components\Generic\Utils\LangUtils;
use UnitEnum;

trait TranslatableAdmin
{
    /**
     * @param object[] $schema
     * @return array
     */
    protected static function setTranslatableLabels(array $schema): array
    {
        return collect($schema)
            ->map(function (object $item): object {
                if (method_exists($item, 'getName') && method_exists($item, 'label')) {
                    $formTranslationKeyEnum = static::getTranslationKeyClass()::tryFrom($item->getName());
                    if (isset($formTranslationKeyEnum)) {
                        $item->label(static::translateEnum($formTranslationKeyEnum));
                    }
                }

                return $item;
            })
            ->toArray();
    }

    protected static function translateComponentProperty(AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey|AdminPagePropertyTranslationKey $enum): string
    {
        return LangUtils::translateValue(static::getTranslationNamespace(), TranslationFilename::ADMIN, $enum->name, static::class);
    }

    protected static function translateEnum(UnitEnum $enum): string
    {
        return LangUtils::translateEnum(static::getTranslationNamespace(), $enum);
    }

    abstract protected static function getTranslationNamespace(): TranslationNamespace;

    /**
     * @return string<UnitEnum>
     */
    abstract protected static function getTranslationKeyClass(): string;
}
