<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Domains\Admin\Admin\Abstracts\Actions\BulkAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

class BulkUpdateAction extends BulkAction
{
    public static function create(): static
    {
        /** @var static $action */
        $action = static::setTranslatableModal(static::setTranslatableLabel(static::make(AdminActionTranslationKey::UPDATE->value)->icon('heroicon-o-pencil')));

        return $action;
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return AdminActionTranslationKey::class;
    }
}
