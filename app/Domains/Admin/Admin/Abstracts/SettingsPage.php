<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Traits\HasNavigationSort;
use Filament\Pages\SettingsPage as BaseSettingsPage;

abstract class SettingsPage extends BaseSettingsPage
{
    use HasNavigationSort;

    /*
     * Translated
     * */

    protected function getTitle(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminPagePropertyTranslationKey::TITLE);

        return $translation;
    }

    protected static function getNavigationLabel(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminPagePropertyTranslationKey::NAVIGATION_LABEL);

        return $translation;
    }

    protected static function getNavigationGroup(): ?string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminPagePropertyTranslationKey::NAVIGATION_GROUP);

        return $translation;
    }
}
