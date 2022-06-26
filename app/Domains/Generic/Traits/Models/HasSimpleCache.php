<?php

namespace App\Domains\Generic\Traits\Models;

use App\Domains\Generic\Classes\SimpleCache;
use App\Domains\Generic\Classes\SimpleCacheFactory;

trait HasSimpleCache
{
    protected SimpleCache $cache;

    protected function initializeHasSimpleCache(): void
    {
        $this->cache = SimpleCacheFactory::createCache(static::class);
    }
}
