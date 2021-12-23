<?php

namespace App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages;

use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getViewTableAction(): Action
    {
        return parent::getViewTableAction()->color('success');
    }
}
