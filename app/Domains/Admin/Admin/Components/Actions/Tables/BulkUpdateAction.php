<?php

namespace App\Domains\Admin\Admin\Components\Actions\Tables;

use App\Domains\Admin\Admin\Abstracts\Actions\Tables\BulkAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

final class BulkUpdateAction extends BulkAction
{
    public static function create(): self
    {
        /** @var self $action */
        $action = self::setTranslatableModal(self::makeTranslated(AdminActionTranslationKey::UPDATE)->icon('heroicon-o-pencil'));

        return $action;
    }
}
