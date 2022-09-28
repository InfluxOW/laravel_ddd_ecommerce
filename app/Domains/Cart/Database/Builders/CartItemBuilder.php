<?php

namespace App\Domains\Cart\Database\Builders;

use App\Domains\Cart\Models\CartItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin CartItem
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class CartItemBuilder extends Builder
{
}
