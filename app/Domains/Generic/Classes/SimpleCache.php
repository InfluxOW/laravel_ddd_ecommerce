<?php

namespace App\Domains\Generic\Classes;

use Closure;

final class SimpleCache
{
    protected array $data = [];

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function getOrSet(string $key, Closure $getValue): mixed
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        $this->set($key, $getValue());

        return $this->getOrSet($key, $getValue);
    }
}
