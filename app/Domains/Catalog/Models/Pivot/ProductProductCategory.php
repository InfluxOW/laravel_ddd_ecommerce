<?php

namespace App\Domains\Catalog\Models\Pivot;

use App\Domains\Catalog\Database\Builders\ProductProductCategoryBuilder;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Domains\Catalog\Models\Pivot\ProductProductCategory
 *
 * @method static ProductProductCategoryBuilder|ProductProductCategory newModelQuery()
 * @method static ProductProductCategoryBuilder|ProductProductCategory newQuery()
 * @method static ProductProductCategoryBuilder|ProductProductCategory query()
 *
 * @mixin \Eloquent
 */
final class ProductProductCategory extends Pivot
{
    /*
     * Internal
     * */

    public function newEloquentBuilder($query): ProductProductCategoryBuilder
    {
        /** @var ProductProductCategoryBuilder<self> $builder */
        $builder = new ProductProductCategoryBuilder($query);

        return $builder;
    }
}
