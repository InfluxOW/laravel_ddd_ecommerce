<?php

namespace App\Components\Mediable\Services\Media;

use App\Domains\Generic\Utils\PathUtils;
use App\Domains\Generic\Utils\StringUtils;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

final class PathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        /** @var Model $model */
        $model = $media->model;

        $folder = StringUtils::pluralBasename($model::class);
        $subfolder = isset($model->slug) ? sprintf('%s-%s', $model->getKey(), $model->slug) : $model->getKey();

        return PathUtils::join([$folder, $subfolder, $media->getKey()]);
    }
}
