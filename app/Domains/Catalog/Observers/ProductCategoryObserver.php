<?php

namespace App\Domains\Catalog\Observers;

use App\Domains\Catalog\Models\ProductCategory;

class ProductCategoryObserver
{
    public function saving(ProductCategory $category): bool
    {
        $result = ($category->parent_id === null) ? true : $category->parent?->depth + 1 <= ProductCategory::MAX_DEPTH;

        return $result && $category->depth <= ProductCategory::MAX_DEPTH;
    }
}