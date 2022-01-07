<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;

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
