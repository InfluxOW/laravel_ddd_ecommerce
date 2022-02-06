<?php

namespace App\Components\Generic\Utils;

final class PathUtils
{
    /**
     * @param string[] $parts
     *
     * @return string
     */
    public static function join(array $parts): string
    {
        return implode(DIRECTORY_SEPARATOR, $parts);
    }
}
