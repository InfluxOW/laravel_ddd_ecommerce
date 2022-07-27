<?php

namespace App\Domains\Admin\Admin\Components\Actions\Delete\Tables;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Admin\Components\Actions\Delete\HasDeleteAction;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\DeleteAction as BaseDeleteAction;
use Illuminate\Database\Eloquent\Model;

final class DeleteAction extends BaseDeleteAction
{
    use HasDeleteAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        /** @phpstan-ignore-next-line */
        return self::baseCreate()->visible(fn (?Model $record, Page|RelationManager $livewire): bool => isset($record) && $livewire instanceof Page ? $livewire::getResource()::canDelete($record) : $livewire->canDelete($record));
    }
}
