<?php

namespace App\Domains\Common\Utils;

final class PathUtils
{
    /**
     * @param string[] $parts
     */
    public static function join(array $parts): string
    {
        return implode(DIRECTORY_SEPARATOR, $parts);
    }
}
