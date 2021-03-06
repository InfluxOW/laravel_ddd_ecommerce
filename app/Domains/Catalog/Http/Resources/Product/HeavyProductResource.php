<?php

namespace App\Domains\Catalog\Http\Resources\Product;

use App\Components\Attributable\Http\Resources\AttributeValueResource;
use App\Components\Mediable\Http\Resources\MediaResource;
use App\Domains\Catalog\Http\Resources\ProductCategory\MediumProductCategoryResource;
use App\Domains\Catalog\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JetBrains\PhpStorm\ArrayShape;
use Stevebauman\Purify\Facades\Purify;

final class HeavyProductResource extends ProductResource
{
    #[ArrayShape(['images' => AnonymousResourceCollection::class, 'slug' => 'string', 'title' => 'string', 'url' => 'string', 'created_at' => 'string|null', 'price' => 'string|optional', 'price_discounted' => 'string|null|optional', 'currency' => 'string', 'categories' => AnonymousResourceCollection::class, 'attributes' => AnonymousResourceCollection::class, 'description' => 'string'])]
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        return array_merge(parent::toArray($request), [
            'images' => MediaResource::collection($product->images),
            'categories' => MediumProductCategoryResource::collection($product->categories->sortBy('title')),
            'attributes' => AttributeValueResource::collection($product->attributeValues->sortBy('attribute.title')),
            'description' => Purify::clean($product->description),
        ]);
    }
}
