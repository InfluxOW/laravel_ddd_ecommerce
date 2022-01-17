<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Models\ProductCategory;
use Filament\Resources\Pages\ViewRecord;

class ViewProductCategory extends ViewRecord
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
}
