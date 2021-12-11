<?php

namespace App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages;

use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use App\Domain\Products\Models\ProductCategory;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    /** @param string $key */
    protected function getRecord($key): Model
    {
        ProductCategory::loadHierarchy();

        return parent::getRecord($key)->append(['path']);
    }
}
