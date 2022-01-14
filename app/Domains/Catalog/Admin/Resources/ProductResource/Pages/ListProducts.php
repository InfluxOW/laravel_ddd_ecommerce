<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\Pages;

use App\Domains\Admin\Admin\Components\Actions\DeleteAction;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getViewTableAction(): Action
    {
        return parent::getViewTableAction()->color('success');
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [DeleteAction::create()]);
    }
}
