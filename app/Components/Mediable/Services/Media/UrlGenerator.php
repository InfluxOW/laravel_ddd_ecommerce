<?php

namespace App\Components\Mediable\Services\Media;

use DateTimeInterface;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

final class UrlGenerator extends DefaultUrlGenerator
{
    public function getTemporaryUrlForPath(string $path, DateTimeInterface $expiration, array $options = []): string
    {
        return $this->getDisk()->temporaryUrl($path, $expiration, $options);
    }
}
