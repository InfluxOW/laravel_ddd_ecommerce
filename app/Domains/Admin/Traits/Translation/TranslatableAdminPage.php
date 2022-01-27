<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;

trait TranslatableAdminPage
{
    use TranslatableAdmin;

    protected function getTitle(): string
    {
        /** @var string $translation */
        $translation = static::translateComponentProperty(AdminPagePropertyTranslationKey::TITLE);

        return $translation;
    }

    protected static function getNavigationLabel(): string
    {
        /** @var string $translation */
        $translation = static::translateComponentProperty(AdminPagePropertyTranslationKey::NAVIGATION_LABEL);

        return $translation;
    }

    protected static function getNavigationGroup(): ?string
    {
        /** @var string $translation */
        $translation = static::translateComponentProperty(AdminPagePropertyTranslationKey::NAVIGATION_GROUP);

        return $translation;
    }
}
