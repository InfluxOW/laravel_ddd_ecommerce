<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class LightProductCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;

        return [
            'title' => $category->title,
            'slug' => $category->slug,
        ];
    }
}
