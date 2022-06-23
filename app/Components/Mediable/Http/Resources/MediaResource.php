<?php

namespace App\Components\Mediable\Http\Resources;

use App\Components\Mediable\Classes\ResponsiveImage;
use App\Components\Mediable\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class MediaResource extends JsonResource
{
    #[ArrayShape(['type' => 'string', 'url' => 'string', 'responsive_images' => AnonymousResourceCollection::class])]
    public function toArray($request): array
    {
        /** @var Media $media */
        $media = $this->resource;

        return [
            'type' => $media->type,
            'url' => $media->getTemporaryUrl(Carbon::now()->addMinutes(10)),
            'responsive_images' => ResponsiveImageResource::collection($media->responsiveImages()->files->sortBy(fn (ResponsiveImage $image): int => $image->width())),
        ];
    }
}
