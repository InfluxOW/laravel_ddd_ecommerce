<?php

namespace App\Domains\Admin\Admin\Components\Actions\Tables;

use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminActionsModals;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;

final class ViewAction extends \Filament\Tables\Actions\ViewAction
{
    use HasTranslatableAdminLabels;
    use HasTranslatableAdminActionsModals;

    public static function create(): self
    {
        /** @var self $action */
        $action = self::setTranslatableLabel(self::make(AdminActionTranslationKey::VIEW->value)
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
