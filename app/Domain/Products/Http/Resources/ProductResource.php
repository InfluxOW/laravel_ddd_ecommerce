<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class ProductResource extends JsonResource
{
    #[ArrayShape(['slug' => 'string', 'title' => "string", 'created_at' => "null|string", 'price' => 'float', 'price_discounted' => "float|null", 'categories' => AnonymousResourceCollection::class, 'attributes' => AnonymousResourceCollection::class, 'description' => "string"])]
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
            'categories' => LightProductCategoryResource::collection($product->categories->sortBy('title')),
            'attributes' => ProductAttributeValueResource::collection($product->attributeValues->sortBy('attribute.title')),
            'description' => $product->description,
        ];
    }
}
