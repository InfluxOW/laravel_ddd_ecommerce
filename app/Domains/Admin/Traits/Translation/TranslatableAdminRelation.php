<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;

trait TranslatableAdminRelation
{
    use TranslatableAdmin;

    protected static function getRecordLabel(): string
    {
        /** @var string $translation */
        $translation = static::translateComponentProperty(AdminRelationPropertyTranslationKey::LABEL);

        return $translation;
    }

    protected static function getPluralRecordLabel(): string
    {
        /** @var string $translation */
        $translation = static::translateComponentProperty(AdminRelationPropertyTranslationKey::PLURAL_LABEL);

        return $translation;
    }

    public static function getTitle(): string
    {
        /** @var string $translation */
        $translation = static::translateComponentProperty(AdminRelationPropertyTranslationKey::TITLE);

        return $translation;
    }
}
