<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Traits\Translation\Internal\TranslatableAdmin;

trait TranslatableAdminResource
{
    use TranslatableAdmin;

    public static function getLabel(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminResourcePropertyTranslationKey::LABEL);

        return $translation;
    }

    public static function getPluralLabel(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminResourcePropertyTranslationKey::PLURAL_LABEL);

        return $translation;
    }

    protected static function getNavigationLabel(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminResourcePropertyTranslationKey::NAVIGATION_LABEL);

        return $translation;
    }

    protected static function getNavigationGroup(): ?string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminResourcePropertyTranslationKey::NAVIGATION_GROUP);

        return $translation;
    }
}
