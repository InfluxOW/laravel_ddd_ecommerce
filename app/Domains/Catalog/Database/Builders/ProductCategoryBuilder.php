<?php

namespace App\Domains\Catalog\Database\Builders;

use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin ProductCategory
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class ProductCategoryBuilder extends Builder
{
    public function hasLimitedDepth(): self
    {
        $this->limitDepth(ProductCategory::MAX_DEPTH);

        return $this;
    }

    public function displayable(): self
    {
        $this->where('product_categories.is_displayable', true);

        return $this;
    }
}
