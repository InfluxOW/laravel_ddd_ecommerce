<?php

namespace App\Domains\Admin\Admin\Components\Actions;

use App\Domains\Admin\Admin\Abstracts\Actions\LinkAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;

class ViewAction extends LinkAction
{
    public static function create(): static
    {
        /** @var static $action */
        $action = static::setTranslatableLabel(static::make(AdminActionTranslationKey::VIEW->value)
            ->url(fn (Model $record, Page $livewire): string => $livewire::getResource()::getUrl('view', ['record' => $record]))
            ->icon('heroicon-o-eye')
            ->hidden(fn (Model $record, Page|RelationManager $livewire): bool => ! ($livewire instanceof Page ? $livewire::getResource()::canView($record) : $livewire::canViewForRecord($livewire->ownerRecord)))
            ->color('success'));

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
