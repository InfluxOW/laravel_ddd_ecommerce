<?php

namespace App\Domains\Catalog\Database\Builders;

use App\Domains\Catalog\Models\Pivot\ProductProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin ProductProductCategory
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class ProductProductCategoryBuilder extends Builder
{
}
