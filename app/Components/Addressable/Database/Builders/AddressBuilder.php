<?php

namespace App\Components\Addressable\Database\Builders;

use App\Components\Addressable\Models\Address;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Address
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class AddressBuilder extends Builder
{
}
