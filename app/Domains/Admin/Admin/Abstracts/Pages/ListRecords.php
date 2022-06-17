<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\ViewAction;
use Filament\Resources\Pages\ListRecords as BaseListRecords;

abstract class ListRecords extends BaseListRecords
{
    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [ViewAction::create(), DeleteAction::create()]);
    }
}
