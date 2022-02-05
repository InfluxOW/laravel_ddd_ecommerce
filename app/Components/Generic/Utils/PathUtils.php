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

    public static function getRootDirectoryPath(): string
    {
        return dirname(env('LARAVEL_SAIL') ? app_path() : $_SERVER['DOCUMENT_ROOT'], 1);
    }
}
