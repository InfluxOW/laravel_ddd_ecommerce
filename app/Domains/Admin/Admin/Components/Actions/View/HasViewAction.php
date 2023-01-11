<?php

namespace App\Domains\Admin\Admin\Components\Actions\View;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

trait HasViewAction
{
    public static function create(): self
    {
        return self::makeTranslated(AdminActionTranslationKey::VIEW)
            ->icon('heroicon-o-eye')
            ->color('success');
    }
}
