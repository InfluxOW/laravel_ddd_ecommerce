<?php

namespace App\Components\Mediable\Classes;

use App\Components\Generic\Utils\PathUtils;
use App\Components\Mediable\Services\Media\UrlGenerator;
use DateTimeInterface;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage as BaseResponsiveImage;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory;

final class ResponsiveImage extends BaseResponsiveImage
{
    public function temporaryUrl(DateTimeInterface $expiration): string
    {
        return $this->getUrlGenerator()->getTemporaryUrlForPath($this->getPathRelativeToRoot(), $expiration);
    }

    public function getPathRelativeToRoot(): string
    {
        $urlGenerator = $this->getUrlGenerator();
        /** @var string $path */
        $path = parse_url($urlGenerator->getResponsiveImagesDirectoryUrl(), PHP_URL_PATH);

        return PathUtils::join([trim($path, '/'), rawurldecode($this->fileName)]);
    }

    private function getUrlGenerator(): UrlGenerator
    {
        $conversionName = ($this->generatedFor() === 'media_library_original') ? '' : $this->generatedFor();
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = UrlGeneratorFactory::createForMedia($this->media, $conversionName);

        return $urlGenerator;
    }
}
