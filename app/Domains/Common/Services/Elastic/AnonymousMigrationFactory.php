<?php

namespace App\Domains\Common\Services\Elastic;

use Elastic\Migrations\Factories\MigrationFactory;
use Elastic\Migrations\Filesystem\MigrationFile;
use Elastic\Migrations\MigrationInterface;
use Illuminate\Filesystem\Filesystem;

final class AnonymousMigrationFactory extends MigrationFactory
{
    public function __construct(private readonly Filesystem $files)
    {
    }

    public function makeFromFile(MigrationFile $file): MigrationInterface
    {
        return $this->files->getRequire($file->path());
    }
}
