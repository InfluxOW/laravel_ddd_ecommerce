<?php

namespace App\Domains\Admin\Admin\Components\Actions\Create;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

/**
 * @internal
 * */
trait HasCreateAction
{
    public static function create(): self
    {
        return self::makeTranslated(AdminActionTranslationKey::CREATE)
            ->icon('heroicon-o-plus')
            ->color('primary');
    }
}
