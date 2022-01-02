<?php

namespace App\Domain\Admin\Traits\Translation;

use App\Domain\Admin\Enums\Translation\AdminPagePropertyTranslationKey;

trait TranslatableAdminPage
{
    use TranslatableAdmin;

    protected function getTitle(): string
    {
        return static::translateComponentProperty(AdminPagePropertyTranslationKey::TITLE);
    }

    protected static function getNavigationLabel(): string
    {
        return static::translateComponentProperty(AdminPagePropertyTranslationKey::NAVIGATION_LABEL);
    }

    protected static function getNavigationGroup(): ?string
    {
        return static::translateComponentProperty(AdminPagePropertyTranslationKey::NAVIGATION_GROUP);
    }
}
