<?php

namespace App\Components\Mediable\Admin\Components\Fields;

use App\Components\Generic\Utils\StringUtils;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

final class MediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->disk(config('filesystems.default'))
            ->collection(fn (?string $model): ?string => ($model === null) ? null : StringUtils::pluralBasename($model))
            ->visibility('private');
    }
}
