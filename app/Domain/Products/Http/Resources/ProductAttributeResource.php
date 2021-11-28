<?php

namespace App\Domain\Products\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Products\Models\ProductAttribute;

class ProductAttributeResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductAttribute $attribute */
        $attribute = $this->resource;

        return [
            'title' => $attribute->title,
            'slug' => $attribute->slug,
        ];
    }
}
