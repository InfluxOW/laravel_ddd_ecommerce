<?php

namespace App\Domains\Admin\Admin\Components\Actions\Tables;

use App\Domains\Admin\Admin\Abstracts\Actions\Tables\BulkAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

final class BulkUpdateAction extends BulkAction
{
    public static function create(): self
    {
        /** @var self $action */
        $action = self::setTranslatableModal(self::setTranslatableLabel(self::make(AdminActionTranslationKey::UPDATE->value)->icon('heroicon-o-pencil')));

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
