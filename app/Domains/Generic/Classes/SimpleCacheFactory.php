<?php

namespace App\Domains\Generic\Classes;

final class SimpleCacheFactory
{
    private static array $instances = [];

    public static function createCache(string $key): SimpleCache
    {
        if (isset(self::$instances[$key])) {
            return self::$instances[$key];
        }

        self::$instances[$key] = new SimpleCache();

        return self::createCache($key);
    }
}
