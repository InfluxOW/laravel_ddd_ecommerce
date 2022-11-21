<?php

namespace App\Domains\Generic\Utils;

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
