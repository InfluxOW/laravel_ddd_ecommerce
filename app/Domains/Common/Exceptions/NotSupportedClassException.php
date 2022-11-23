<?php

namespace App\Domains\Common\Exceptions;

use Exception;

final class NotSupportedClassException extends Exception
{
    /**
     * @param class-string   $class
     * @param class-string[] $supportedClasses
     */
    public static function because(string $class, array $supportedClasses): self
    {
        return new self(sprintf('Not supported class %s. Supported classes are %s.', $class, implode(', ', $supportedClasses)));
    }
}
