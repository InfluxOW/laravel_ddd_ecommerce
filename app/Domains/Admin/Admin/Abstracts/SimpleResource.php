<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Traits\HasNavigationSort;
use Filament\Resources\Resource as BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;

abstract class SimpleResource extends BaseResource
{
    use HasNavigationSort;
    use Macroable;

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        if (static::canView($record)) {
            return static::getUrl('view', ['record' => $record]);
        }

        if (static::canEdit($record)) {
            return static::getUrl('edit', ['record' => $record]);
        }

        return null;
    }

    /*
     * Translated
     * */

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
