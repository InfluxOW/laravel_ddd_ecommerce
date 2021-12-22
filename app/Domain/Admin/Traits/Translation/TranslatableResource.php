<?php

namespace App\Domain\Admin\Traits\Translation;

use App\Domain\Admin\Enums\AdminTranslationKey;

trait TranslatableResource
{
    use Translatable;

    public static function getLabel(): string
    {
        return self::translate(AdminTranslationKey::LABEL);
    }

    public static function getPluralLabel(): string
    {
        return self::translate(AdminTranslationKey::PLURAL_LABEL);
    }

    protected static function getNavigationLabel(): string
    {
        return self::translate(AdminTranslationKey::NAVIGATION_LABEL);
    }

    protected static function getNavigationGroup(): ?string
    {
        return self::translate(AdminTranslationKey::NAVIGATION_GROUP);
    }
}
