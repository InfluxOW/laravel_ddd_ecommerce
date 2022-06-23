<?php

namespace App\Domains\Catalog\Http\Resources;

use App\Domains\Catalog\Models\ProductAttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class ProductAttributeValueResource extends JsonResource
{
    #[ArrayShape(['value' => 'string|int|bool|float', 'attribute' => ProductAttributeResource::class])]
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
