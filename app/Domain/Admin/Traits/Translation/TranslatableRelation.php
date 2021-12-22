<?php

namespace App\Domain\Admin\Traits\Translation;

use App\Domain\Admin\Enums\AdminTranslationKey;

trait TranslatableRelation
{
    use Translatable;

    protected static function getRecordLabel(): string
    {
        return self::translate(AdminTranslationKey::LABEL);
    }

    protected static function getPluralRecordLabel(): string
    {
        return self::translate(AdminTranslationKey::PLURAL_LABEL);
    }

    public static function getTitle(): string
    {
        return self::translate(AdminTranslationKey::TITLE);
    }
}
