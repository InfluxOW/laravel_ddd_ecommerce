<?php

namespace App\Domains\Admin\Admin\Components\Actions\Edit\Tables;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Admin\Components\Actions\Edit\HasEditAction;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\EditAction as BaseEditAction;
use Illuminate\Database\Eloquent\Model;

final class EditAction extends BaseEditAction
{
    use HasEditAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        return self::baseCreate()->visible(fn (Model $record, Page|RelationManager $livewire): bool => $livewire instanceof Page ? $livewire::getResource()::canEdit($record) : $livewire->canEdit($record));
    }
}
