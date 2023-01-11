<?php

namespace App\Domains\Admin\Admin\Components\Actions\Delete;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

trait HasDeleteAction
{
    public static function create(): static
    {
        return self::makeTranslated(AdminActionTranslationKey::DELETE)
            ->requiresConfirmation()
            ->icon('heroicon-o-trash')
            ->color('danger');
    }
}
