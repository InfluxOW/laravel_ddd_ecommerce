<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use Filament\Tables\Actions\BulkAction;

final class BulkUpdateAction extends BulkAction
{
    public static function create(): self
    {
        return self::makeTranslated(AdminActionTranslationKey::UPDATE)->icon('heroicon-o-pencil');
    }
}
