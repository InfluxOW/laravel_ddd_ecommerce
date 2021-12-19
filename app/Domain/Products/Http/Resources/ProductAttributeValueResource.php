<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\ProductAttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class ProductAttributeValueResource extends JsonResource
{
    #[ArrayShape(['value' => "mixed", 'attribute' => JsonResource::class])]
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
