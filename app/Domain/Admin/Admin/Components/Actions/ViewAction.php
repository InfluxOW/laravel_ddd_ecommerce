<?php

namespace App\Domain\Admin\Admin\Components\Actions;

use App\Domain\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Generic\Utils\LangUtils;
use Filament\Tables\Actions\LinkAction;

class ViewAction extends LinkAction
{
    public static function create(): static
    {
        return self::make(AdminActionTranslationKey::VIEW->value)->label(LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminActionTranslationKey::VIEW))->color('success');
    }
}
