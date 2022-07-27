<?php

namespace App\Domains\Admin\Admin\Components\Actions\Delete\Pages;

use App\Domains\Admin\Admin\Components\Actions\Delete\HasDeleteAction;
use Filament\Pages\Actions\DeleteAction as BaseDeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;

final class DeleteAction extends BaseDeleteAction
{
    use HasDeleteAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        return self::baseCreate()->visible(fn (Page $livewire): bool => $livewire instanceof EditRecord && $livewire::getResource()::canDelete($livewire->getRecord()));
    }
}
