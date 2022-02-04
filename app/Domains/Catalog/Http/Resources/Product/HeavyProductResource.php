<?php

namespace App\Domains\Catalog\Http\Resources\Product;

use App\Components\Mediable\Http\Resources\MediaResource;
use App\Domains\Catalog\Http\Resources\ProductAttributeValueResource;
use App\Domains\Catalog\Http\Resources\ProductCategory\MediumProductCategoryResource;
use App\Domains\Catalog\Models\Product;

final class HeavyProductResource extends ProductResource
{
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        return array_merge(parent::toArray($request), [
            'images' => MediaResource::collection($product->images),
            'categories' => MediumProductCategoryResource::collection($product->categories->sortBy('title')),
            'attributes' => ProductAttributeValueResource::collection($product->attributeValues->sortBy('attribute.title')),
            'description' => $product->description,
        ]);
    }
}
