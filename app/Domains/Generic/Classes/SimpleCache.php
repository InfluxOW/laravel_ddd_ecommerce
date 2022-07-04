<?php

namespace App\Domains\Generic\Classes;

use Closure;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Cache\Events\KeyWritten;

final class SimpleCache
{
    protected array $data = [];

    private function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    private function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    private function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function remember(string $key, Closure $callback): mixed
    {
        if ($this->has($key)) {
            $value = $this->get($key);

            $this->event(CacheHit::class, $key, $value);

            return $value;
        }

        $this->event(CacheMissed::class, $key);

        $value = $callback();

        $this->set($key, $value);

        $this->event(KeyWritten::class, $key, $value);

        return $value;
    }

    private function event(string $event, mixed ...$args): void
    {
        if (app()->isRunningSeeders()) {
            return;
        }

        event(new $event(...$args));
    }
}
