<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        return [
            'slug' => $product->slug,
            'title' => $product->title,
            'created_at' => ($product->created_at === null) ? null : $product->created_at->format('d M Y H:i:s'),
            'price' => $product->price->roubles(),
            'price_discounted' => ($product->price_discounted === null) ? null : $product->price_discounted->roubles(),
            'category' => LightProductCategoryResource::make($product->category),
            'attributes' => ProductAttributeValueResource::collection($product->attributeValues->sortBy('attribute.title')),
            'description' => $product->description,
        ];
    }
}
