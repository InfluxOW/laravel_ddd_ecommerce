<?php

namespace App\Domains\Catalog\Observers;

use App\Domains\Catalog\Models\Pivot\ProductProductCategory;
use App\Domains\Catalog\Models\ProductCategory;

final class ProductProductCategoryObserver
{
    public function created(ProductProductCategory $model): void
    {
        if (app()->isRunningSeeders()) {
            return;
        }

        ProductCategory::loadHierarchy();
    }

    public function deleted(ProductProductCategory $model): void
    {
        ProductCategory::loadHierarchy();
    }
}
