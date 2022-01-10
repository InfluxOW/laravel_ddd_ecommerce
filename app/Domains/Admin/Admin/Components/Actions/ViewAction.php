<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Utils\LangUtils;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Model;

class ViewAction extends LinkAction
{
    public static function create(): static
    {
        return self::make(AdminActionTranslationKey::VIEW->value)
            ->label(LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminActionTranslationKey::VIEW))
            ->url(fn (Model $record, Page $livewire): string => $livewire::getResource()::getUrl('view', ['record' => $record]))
            ->icon('heroicon-o-eye')
            ->hidden(fn (Model $record, Page $livewire): bool => ! $livewire::getResource()::canView($record))
            ->color('success');
    }
}
