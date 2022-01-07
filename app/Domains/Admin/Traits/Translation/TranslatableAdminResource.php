<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;

trait TranslatableAdminResource
{
    use TranslatableAdmin;

    public static function getLabel(): string
    {
        return static::translateComponentProperty(AdminResourcePropertyTranslationKey::LABEL);
    }

    public static function getPluralLabel(): string
    {
        return static::translateComponentProperty(AdminResourcePropertyTranslationKey::PLURAL_LABEL);
    }

    protected static function getNavigationLabel(): string
    {
        return static::translateComponentProperty(AdminResourcePropertyTranslationKey::NAVIGATION_LABEL);
    }

    protected static function getNavigationGroup(): ?string
    {
        return static::translateComponentProperty(AdminResourcePropertyTranslationKey::NAVIGATION_GROUP);
    }
}
