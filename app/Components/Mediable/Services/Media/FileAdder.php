<?php

namespace App\Components\Mediable\Services\Media;

use Spatie\MediaLibrary\MediaCollections\FileAdder as BaseFileAdder;
use Spatie\MediaLibrary\MediaCollections\Filesystem;

final class FileAdder extends BaseFileAdder
{
    public function __construct(Filesystem $fileSystem)
    {
        parent::__construct($fileSystem);

        $this->withResponsiveImages();
    }
}
