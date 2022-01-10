<?php

namespace App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages;

use App\Domains\Admin\Admin\Components\Actions\DeleteAction;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListProductAttributes extends ListRecords
{
    protected static string $resource = ProductAttributeResource::class;

    protected function getViewTableAction(): Action
    {
        return parent::getViewTableAction()->color('success');
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [DeleteAction::create()]);
    }
}
