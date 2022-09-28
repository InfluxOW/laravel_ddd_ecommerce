<?php

namespace App\Domains\Admin\Database\Builders;

use App\Domains\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Admin
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class AdminBuilder extends Builder
{
}
