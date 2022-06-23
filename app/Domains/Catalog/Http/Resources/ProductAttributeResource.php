<?php

namespace App\Domains\Catalog\Http\Resources;

use App\Domains\Catalog\Models\ProductAttribute;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class ProductAttributeResource extends JsonResource
{
    #[ArrayShape(['slug' => 'string', 'title' => 'string', 'values_type' => 'string'])]
    public function toArray($request): array
    {
        /** @var ProductAttribute $attribute */
        $attribute = $this->resource;

        return [
            'slug' => $attribute->slug,
            'title' => $attribute->title,
            'values_type' => $attribute->values_type->responseValueType()->name,
        ];
    }
}
