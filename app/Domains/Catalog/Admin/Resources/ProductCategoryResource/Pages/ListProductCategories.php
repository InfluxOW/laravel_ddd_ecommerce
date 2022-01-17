<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Models\ProductCategory;

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
}
