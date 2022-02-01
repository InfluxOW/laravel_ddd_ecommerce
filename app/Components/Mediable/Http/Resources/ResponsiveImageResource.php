<?php

namespace App\Components\Mediable\Http\Resources;

use App\Components\Mediable\Classes\ResponsiveImage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

final class ResponsiveImageResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ResponsiveImage $image */
        $image = $this->resource;

        return [
            'width' => $image->width(),
            'height' => $image->height(),
            'url' => $image->temporaryUrl(Carbon::now()->addMinutes(10)),
        ];
    }
}
