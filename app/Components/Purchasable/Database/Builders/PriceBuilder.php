<?php

namespace App\Components\Purchasable\Database\Builders;

use App\Components\Purchasable\Models\Price;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Price
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class PriceBuilder extends Builder
{
}
