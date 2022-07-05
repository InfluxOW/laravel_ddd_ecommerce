<?php

namespace App\Application\Providers;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

final class CacheServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @phpstan-ignore-next-line */
        Cache::macro('simple', fn (): Repository => Cache::store('array'));

        /** @phpstan-ignore-next-line */
        Cache::macro('rememberInArray', fn (string $key, Closure $callback): mixed => Cache::simple()->remember($key, app()->runningInConsole() ? null : config('octane.max_execution_time'), $callback));
    }
}
