<?php

namespace App\Components\Attributable\Database\Builders;

use App\Components\Attributable\Models\AttributeValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin AttributeValue
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class AttributeValueBuilder extends Builder
{
}
