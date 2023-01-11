<?php

namespace App\Domains\Admin\Admin\Components\Actions\Edit;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

trait HasEditAction
{
    public static function create(): self
    {
        return self::makeTranslated(AdminActionTranslationKey::EDIT)
            ->icon('heroicon-o-pencil')
            ->color('primary');
    }
}
