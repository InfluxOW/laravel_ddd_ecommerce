<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Domains\Admin\Admin\Abstracts\Actions\LinkAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class DeleteAction extends LinkAction
{
    public static function create(): static
    {
        /** @var static $action */
        $action = static::setTranslatableModal(static::setTranslatableLabel(static::make(AdminActionTranslationKey::DELETE->value)
            ->requiresConfirmation()
            ->action(fn (?Model $record) => $record?->delete())
            ->icon('heroicon-o-trash')
            ->hidden(fn (?Model $record, Page $livewire): bool => ! $livewire::getResource()::canDelete($record))
            ->color('danger')));

        return $action;
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return AdminActionTranslationKey::class;
    }
}
