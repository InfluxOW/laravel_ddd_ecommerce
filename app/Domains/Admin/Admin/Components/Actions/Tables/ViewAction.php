<?php

namespace App\Domains\Admin\Admin\Components\Actions\Tables;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction as BaseViewAction;
use Illuminate\Database\Eloquent\Model;

final class ViewAction extends BaseViewAction
{
    public static function create(): self
    {
        return self::makeTranslated(AdminActionTranslationKey::VIEW)
            ->url(fn (Model $record, Page|RelationManager $livewire): ?string => $livewire instanceof Page ? $livewire::getResource()::getUrl('view', ['record' => $record]) : null)
            ->icon('heroicon-o-eye')
            ->visible(fn (Model $record, Page|RelationManager $livewire): bool => $livewire instanceof Page ? $livewire::getResource()::canView($record) : $livewire::canViewForRecord($livewire->ownerRecord))
            ->color('success');
    }
}
