<?php

namespace App\Domains\Catalog\Http\Resources\Product;

use App\Domains\Catalog\Http\Resources\ProductCategory\LightProductCategoryResource;
use App\Domains\Catalog\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JetBrains\PhpStorm\ArrayShape;

class LightProductResource extends ProductResource
{
    #[ArrayShape(['media' => AnonymousResourceCollection::class, 'slug' => 'string', 'title' => "string", 'url' => "string", 'created_at' => "null|string", 'price' => 'string|optional', 'price_discounted' => "string|null|optional", 'currency' => "string", 'categories' => AnonymousResourceCollection::class])]
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        return array_merge(parent::toArray($request), [
            'categories' => LightProductCategoryResource::collection($product->categories->sortBy('title')),
        ]);
    }
}
