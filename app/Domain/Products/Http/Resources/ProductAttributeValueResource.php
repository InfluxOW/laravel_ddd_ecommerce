<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\ProductAttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeValueResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductAttributeValue $value */
        $value = $this->resource;

        return [
            'value' => $value->value,
            'attribute' => ProductAttributeResource::make($value->attribute),
        ];
    }
}
