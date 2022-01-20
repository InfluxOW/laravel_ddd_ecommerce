<?php

namespace App\Domains\Catalog\Observers;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Components\Generic\Utils\AppUtils;

class ProductObserver
{
    public function created(Product $product): void
    {
        if (AppUtils::runningSeeders()) {
            return;
        }

        ProductCategory::loadHierarchy();
    }

    public function updated(Product $product): void
    {
        if (AppUtils::runningSeeders()) {
            return;
        }

        ProductCategory::loadHierarchy();
    }

    public function deleted(Product $product): void
    {
        ProductCategory::loadHierarchy();
    }
}
