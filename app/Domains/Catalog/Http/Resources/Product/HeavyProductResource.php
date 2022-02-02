<?php

namespace App\Domains\Catalog\Http\Resources\Product;

use App\Domains\Catalog\Http\Resources\ProductAttributeValueResource;
use App\Domains\Catalog\Http\Resources\ProductCategory\MediumProductCategoryResource;
use App\Domains\Catalog\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JetBrains\PhpStorm\ArrayShape;

final class HeavyProductResource extends LightProductResource
{
    #[ArrayShape(['media' => AnonymousResourceCollection::class, 'slug' => 'string', 'title' => "string", 'url' => "string", 'created_at' => "null|string", 'price' => 'string|optional', 'price_discounted' => "string|null|optional", 'currency' => "string", 'categories' => AnonymousResourceCollection::class, 'attributes' => AnonymousResourceCollection::class, 'description' => "string"])]
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        return array_merge(parent::toArray($request), [
            'categories' => MediumProductCategoryResource::collection($product->categories->sortBy('title')),
            'attributes' => ProductAttributeValueResource::collection($product->attributeValues->sortBy('attribute.title')),
            'description' => $product->description,
        ]);
    }
}
