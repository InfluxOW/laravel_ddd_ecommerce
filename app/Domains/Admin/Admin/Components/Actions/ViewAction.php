<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Utils\LangUtils;
use Filament\Tables\Actions\LinkAction;

class ViewAction extends LinkAction
{
    public static function create(): static
    {
        return self::make(AdminActionTranslationKey::VIEW->value)->label(LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminActionTranslationKey::VIEW))->color('success');
    }
}
