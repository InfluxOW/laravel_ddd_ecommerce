<?php

namespace App\Domain\Admin\Traits\Translation;

use App\Domain\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domain\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domain\Generic\Lang\Enums\TranslationFilename;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Utils\LangUtils;
use BackedEnum;

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

    protected static function translateComponentProperty(AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey $enum): string
    {
        return LangUtils::translateValue(static::getTranslationNamespace(), TranslationFilename::ADMIN, $enum->value, static::class);
    }

    protected static function translateEnum(BackedEnum $enum): string
    {
        return LangUtils::translateEnum(static::getTranslationNamespace(), $enum);
    }

    abstract protected static function getTranslationNamespace(): TranslationNamespace;

    /**
     * @return string<BackedEnum>
     */
    abstract protected static function getTranslationKeyClass(): string;
}
