<?php

namespace App\Components\Attributable\Http\Resources;

use App\Components\Attributable\Models\AttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class AttributeValueResource extends JsonResource
{
    #[ArrayShape(['value' => 'string|int|bool|float', 'attribute' => AttributeResource::class])]
    public function toArray($request): array
    {
        /** @var AttributeValue $value */
        $value = $this->resource;

        return [
            'value' => $value->value,
            'attribute' => AttributeResource::make($value->attribute),
        ];
    }
}
