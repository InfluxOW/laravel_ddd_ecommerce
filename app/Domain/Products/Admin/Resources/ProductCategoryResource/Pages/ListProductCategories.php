<?php

namespace App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages;

use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\LinkAction;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getViewLinkTableAction(): LinkAction
    {
        return parent::getViewLinkTableAction()->color('success');
    }
}
