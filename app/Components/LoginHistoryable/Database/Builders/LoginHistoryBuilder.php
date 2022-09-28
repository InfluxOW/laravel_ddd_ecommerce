<?php

namespace App\Components\LoginHistoryable\Database\Builders;

use App\Components\LoginHistoryable\Models\LoginHistory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\Builder;

/**
 * @mixin LoginHistory
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @phpstan-extends EloquentBuilder<TModelClass>
 * */
final class LoginHistoryBuilder extends Builder
{
}
