<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use Filament\Tables\Actions\DeleteBulkAction as BaseDeleteBulkAction;

final class DeleteBulkAction extends BaseDeleteBulkAction
{
    public static function create(): self
    {
        return self::makeTranslated(AdminActionTranslationKey::BULK_DELETE)->requiresConfirmation()->icon('heroicon-o-trash');
    }
}
