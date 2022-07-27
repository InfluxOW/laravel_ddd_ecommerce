<?php

namespace App\Domains\Admin\Admin\Components\Actions\View\Tables;

use App\Domains\Admin\Admin\Components\Actions\View\HasViewAction;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction as BaseViewAction;
use Illuminate\Database\Eloquent\Model;

final class ViewAction extends BaseViewAction
{
    use HasViewAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        return self::baseCreate()->visible(fn (Model $record, Page|RelationManager $livewire): bool => $livewire instanceof Page ? $livewire::getResource()::canView($record) : $livewire::canViewForRecord($livewire->ownerRecord));
    }
}
