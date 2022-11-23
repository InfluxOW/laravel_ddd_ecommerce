<?php

namespace App\Domains\Common\Utils;

final class FileUtils
{
    public static function getDirectoryContent(string $directory): array
    {
        if (is_dir($directory)) {
            $files = scandir($directory);

            if (is_array($files)) {
                return collect($files)->filter(fn (string $filename): bool => collect(['.', '..'])->doesntContain($filename))->values()->toArray();
            }
        }

        return [];
    }
}
