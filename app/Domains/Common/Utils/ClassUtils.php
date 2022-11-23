<?php

namespace App\Domains\Common\Utils;

use App\Domains\Common\Exceptions\NotSupportedClassException;

final class ClassUtils
{
    /**
     * @param class-string   $class
     * @param class-string[] $supportedClasses
     *
     * @throws NotSupportedClassException
     */
    public static function blockNotSupportedClasses(string $class, array $supportedClasses): void
    {
        /** @var array<string, class-string> $parents */
        $parents = class_parents($class);

        if (array_intersect([$class, ...array_values($parents)], $supportedClasses) === []) {
            throw NotSupportedClassException::because($class, $supportedClasses);
        }
    }
}
