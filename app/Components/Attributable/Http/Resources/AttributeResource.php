<?php

namespace App\Components\Attributable\Http\Resources;

use App\Components\Attributable\Models\Attribute;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class AttributeResource extends JsonResource
{
    #[ArrayShape(['slug' => 'string', 'title' => 'string', 'values_type' => 'string'])]
    public function toArray($request): array
    {
        /** @var Attribute $attribute */
        $attribute = $this->resource;

        return [
            'slug' => $attribute->slug,
            'title' => $attribute->title,
            'values_type' => $attribute->values_type->responseValueType()->name,
        ];
    }
}
