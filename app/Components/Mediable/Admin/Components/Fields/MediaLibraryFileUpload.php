<?php

namespace App\Components\Mediable\Admin\Components\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

final class MediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->disk(config('filesystems.default'))
            ->visibility('private');
    }
}
