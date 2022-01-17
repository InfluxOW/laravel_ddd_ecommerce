<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\DeleteAction;
use Filament\Resources\Pages\ListRecords as BaseListRecords;
use Filament\Tables\Actions\Action;

abstract class ListRecords extends BaseListRecords
{
    protected function getViewTableAction(): Action
    {
        return parent::getViewTableAction()->color('success');
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [DeleteAction::create()]);
    }
}
