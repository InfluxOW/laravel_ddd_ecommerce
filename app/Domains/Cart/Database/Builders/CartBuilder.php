<?php

namespace App\Domains\Cart\Database\Builders;

use App\Domains\Cart\Models\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Cart
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class CartBuilder extends Builder
{
}
