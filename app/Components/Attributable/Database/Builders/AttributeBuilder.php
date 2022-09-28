<?php

namespace App\Components\Attributable\Database\Builders;

use App\Components\Attributable\Models\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Attribute
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class AttributeBuilder extends Builder
{
}
