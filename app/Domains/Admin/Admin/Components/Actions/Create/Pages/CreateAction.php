<?php

namespace App\Domains\Admin\Admin\Components\Actions\Create\Pages;

use App\Domains\Admin\Admin\Components\Actions\Create\HasCreateAction;
use Filament\Pages\Actions\CreateAction as BaseCreateAction;
use Filament\Resources\Pages\Page;

final class CreateAction extends BaseCreateAction
{
    use HasCreateAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        return self::baseCreate()->visible(fn (Page $livewire): bool => $livewire::getResource()::canCreate());
    }
}
