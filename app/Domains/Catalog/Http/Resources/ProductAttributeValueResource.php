<?php

namespace App\Domains\Catalog\Http\Resources;

use App\Domains\Catalog\Models\ProductAttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductAttributeValueResource extends JsonResource
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
