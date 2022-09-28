<?php

namespace App\Components\Mediable\Database\Builders;

use App\Components\Mediable\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Media
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class MediaBuilder extends Builder
{
}
