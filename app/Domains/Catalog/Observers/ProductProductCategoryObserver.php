<?php

namespace App\Domains\Catalog\Observers;

use App\Domains\Catalog\Models\Pivot\ProductProductCategory;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Generic\Utils\AppUtils;

final class ProductProductCategoryObserver
{
    public function created(ProductProductCategory $model): void
    {
        if (AppUtils::runningSeeders()) {
            return;
        }

        ProductCategory::loadHierarchy();
    }

    public function deleted(ProductProductCategory $model): void
    {
        ProductCategory::loadHierarchy();
    }
}
