<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Components\Generic\Utils\LangUtils;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Providers\DomainServiceProvider;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Model;

class DeleteAction extends LinkAction
{
    public static function create(): static
    {
        return self::make(AdminActionTranslationKey::DELETE->value)
            ->label(LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminActionTranslationKey::DELETE))
            ->requiresConfirmation()
            ->action(fn (?Model $record) => $record?->delete())
            ->modalHeading(fn (Page $livewire, ?Model $record) => LangUtils::translateEnum(DomainServiceProvider::TRANSLATION_NAMESPACE, AdminActionTranslationKey::DELETE) . ' ' . $livewire::getResource()::getRecordTitle($record))
            ->icon('heroicon-o-trash')
            ->hidden(fn (?Model $record, Page $livewire): bool => ! $livewire::getResource()::canDelete($record))
            ->color('danger');
    }
}
