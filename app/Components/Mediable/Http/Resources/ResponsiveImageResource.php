<?php

namespace App\Components\Mediable\Http\Resources;

use App\Components\Mediable\Classes\ResponsiveImage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class ResponsiveImageResource extends JsonResource
{
    #[ArrayShape(['width' => 'int', 'height' => 'int', 'url' => 'string'])]
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
