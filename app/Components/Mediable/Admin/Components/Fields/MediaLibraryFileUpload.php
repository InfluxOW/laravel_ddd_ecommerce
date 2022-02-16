<?php

namespace App\Components\Mediable\Admin\Components\Fields;

use Carbon\Carbon;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class MediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->disk(config('filesystems.default'))
            ->visibility('private');

        // Temporary fix until it's fixed in Filament
        $this->getUploadedFileUrlUsing(function (SpatieMediaLibraryFileUpload $component, string $file): ?string {
            if ($component->getRecord() === null) {
                return null;
            }

            $mediaClass = config('media-library.media_model', Media::class);
            /** @var ?Media $media */
            $media = $mediaClass::findByUuid($file);

            return ($component->getVisibility() === 'private') ? $media?->getTemporaryUrl(Carbon::now()->addMinutes(5)) : $media?->getUrl();
        });
    }
}
