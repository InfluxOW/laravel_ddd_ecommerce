<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\ProductAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductAttribute $attribute */
        $attribute = $this->resource;

        return [
            'title' => $attribute->title,
            'slug' => $attribute->slug,
            'values_type' => $attribute->values_type->readableType()->value,
        ];
    }
}
