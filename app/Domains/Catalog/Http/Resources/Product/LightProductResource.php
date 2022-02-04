<?php

namespace App\Domains\Catalog\Http\Resources\Product;

use App\Components\Mediable\Http\Resources\MediaResource;
use App\Domains\Catalog\Http\Resources\ProductCategory\LightProductCategoryResource;
use App\Domains\Catalog\Models\Product;

class LightProductResource extends ProductResource
{
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        return array_merge(parent::toArray($request), [
            'image' => MediaResource::make($product->image),
            'categories' => LightProductCategoryResource::collection($product->categories->sortBy('title')),
        ]);
    }
}
