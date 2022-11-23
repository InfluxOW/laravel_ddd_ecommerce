<?php

namespace App\Domains\Common\Mixins;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

final class CacheMixin
{
    public function simple(): Closure
    {
        return fn (): Repository => Cache::store('array');
    }

    public function rememberInArray(): Closure
    {
        return fn (string $key, Closure $callback): mixed => Cache::simple()->remember($key, app()->runningInConsole() ? null : config('octane.max_execution_time'), $callback);
    }
}
