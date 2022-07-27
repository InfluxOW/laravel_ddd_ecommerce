<?php

namespace App\Domains\Admin\Admin\Components\Actions\Create\Tables;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Admin\Components\Actions\Create\HasCreateAction;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\CreateAction as BaseCreateAction;

final class CreateAction extends BaseCreateAction
{
    use HasCreateAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        return self::baseCreate()->visible(fn (Page|RelationManager $livewire): bool => $livewire instanceof Page ? $livewire::getResource()::canCreate() : $livewire->canCreate());
    }
}
