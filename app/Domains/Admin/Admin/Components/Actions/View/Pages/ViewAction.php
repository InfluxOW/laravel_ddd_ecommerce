<?php

namespace App\Domains\Admin\Admin\Components\Actions\View\Pages;

use App\Domains\Admin\Admin\Components\Actions\View\HasViewAction;
use Filament\Pages\Actions\ViewAction as BaseViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;

final class ViewAction extends BaseViewAction
{
    use HasViewAction {
        create as baseCreate;
    }

    public static function create(): self
    {
        return self::baseCreate()->visible(fn (Page $livewire): bool => $livewire instanceof EditRecord && $livewire::getResource()::canView($livewire->getRecord()))->color('secondary');
    }
}
