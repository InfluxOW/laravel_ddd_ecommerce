<?php

namespace App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages;

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
}
