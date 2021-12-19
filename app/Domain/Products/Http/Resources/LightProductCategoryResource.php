<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class LightProductCategoryResource extends JsonResource
{
    #[ArrayShape(['slug' => "string", 'title' => "string"])]
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;

        return [
            'slug' => $category->slug,
            'title' => $category->title,
        ];
    }
}
