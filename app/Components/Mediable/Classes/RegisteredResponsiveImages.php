<?php

namespace App\Components\Mediable\Classes;

use Carbon\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\ResponsiveImages\RegisteredResponsiveImages as BaseRegisteredResponsiveImages;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage as BaseResponsiveImage;

final class RegisteredResponsiveImages extends BaseRegisteredResponsiveImages
{
    public function __construct(Media $media, string $conversionName = '')
    {
        parent::__construct($media, $conversionName);

        $this->files = $this->files->map(fn (BaseResponsiveImage $image) => new ResponsiveImage($image->fileName, $image->media));
    }

    public function getTemporaryUrls(): array
    {
        return $this->files
            ->map(fn (ResponsiveImage $responsiveImage) => $responsiveImage->temporaryUrl(Carbon::now()->addMinutes(10)))
            ->values()
            ->toArray();
    }
}
