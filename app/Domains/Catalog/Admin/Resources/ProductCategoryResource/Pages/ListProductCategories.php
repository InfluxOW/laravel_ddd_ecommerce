<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Admin\Admin\Components\Actions\DeleteAction;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Models\ProductCategory;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;

    /**
     * @return void
     */
    public function bootIfNotBooted()
    {
        parent::bootIfNotBooted();

        ProductCategory::loadHeavyHierarchy();
    }

    protected function getViewTableAction(): Action
    {
        return parent::getViewTableAction()->color('success');
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [DeleteAction::create()]);
    }
}
