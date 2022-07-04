<?php

namespace App\Application\Providers;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

final class CacheServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @phpstan-ignore-next-line */
        Cache::macro('rememberInArray', fn (string $key, Closure $callback): mixed => Cache::store('array')->remember($key, app()->runningInConsole() ? null : config('octane.max_execution_time'), $callback));
    }
}
