<?php

namespace App\Domain\Admin\Traits\Translation;

use App\Domain\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;

trait TranslatableAdminRelation
{
    use TranslatableAdmin;

    protected static function getRecordLabel(): string
    {
        return static::translateComponentProperty(AdminRelationPropertyTranslationKey::LABEL);
    }

    protected static function getPluralRecordLabel(): string
    {
        return static::translateComponentProperty(AdminRelationPropertyTranslationKey::PLURAL_LABEL);
    }

    public static function getTitle(): string
    {
        return static::translateComponentProperty(AdminRelationPropertyTranslationKey::TITLE);
    }
}
