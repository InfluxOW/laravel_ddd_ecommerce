<?php

namespace App\Domains\Catalog\Observers;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;

final class ProductObserver
{
    public function created(Product $product): void
    {
        if (app()->isRunningSeeders()) {
            return;
        }

        ProductCategory::loadHierarchy();
    }

    public function updated(Product $product): void
    {
        if (app()->isRunningSeeders()) {
            return;
        }

        ProductCategory::loadHierarchy();
    }

    public function deleted(Product $product): void
    {
        ProductCategory::loadHierarchy();
    }
}
