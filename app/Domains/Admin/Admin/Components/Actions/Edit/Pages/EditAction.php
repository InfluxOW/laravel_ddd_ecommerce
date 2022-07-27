<?php

namespace App\Domains\Admin\Admin\Components\Actions\Edit\Pages;

use App\Domains\Admin\Admin\Components\Actions\Edit\HasEditAction;
use Filament\Pages\Actions\EditAction as BaseEditAction;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;

final class EditAction extends BaseEditAction
{
    use HasEditAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        return self::baseCreate()->visible(fn (Page $livewire): bool => $livewire instanceof ViewRecord && $livewire::getResource()::canEdit($livewire->getRecord()));
    }
}
