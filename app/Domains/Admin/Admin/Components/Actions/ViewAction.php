<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Components\Generic\Utils\LangUtils;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Providers\DomainServiceProvider;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
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
            ->hidden(fn (Model $record, Page|RelationManager $livewire): bool => ! ($livewire instanceof Page ? $livewire::getResource()::canView($record) : $livewire::canViewForRecord($livewire->ownerRecord)))
            ->color('success');
    }
}
