<?php

namespace App\Domains\Admin\Admin\Components\Actions\Tables;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

final class DeleteAction extends \Filament\Tables\Actions\DeleteAction
{
    public static function create(): self
    {
        return self::makeTranslated(AdminActionTranslationKey::DELETE)
            ->requiresConfirmation()
            ->action(fn (?Model $record) => $record?->delete())
            ->icon('heroicon-o-trash')
            /** @phpstan-ignore-next-line */
            ->visible(fn (?Model $record, Page|RelationManager $livewire): bool => isset($record) && $livewire instanceof Page ? $livewire::getResource()::canDelete($record) : $livewire->canDelete($record))
            ->color('danger');
    }
}
