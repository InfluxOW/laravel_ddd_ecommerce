<?php

namespace App\Domains\Catalog\Http\Resources\ProductCategory;

use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class HeavyProductCategoryResource extends JsonResource
{
    #[ArrayShape(['slug' => "string", 'title' => "string", 'url' => "string", 'description' => "string", 'products_count' => "int", 'children' => AnonymousResourceCollection::class . '|' . 'optional'])]
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;

        return array_merge(LightProductCategoryResource::make($this->resource)->toArray($request), [
            'description' => $category->description,
            'products_count' => $category->overall_products_count,
            'children' => $this->whenLoaded(
                'children',
                $this->when(
                    $category->children->count() > 0,
                    self::collection($category->children)
                )
            ),
        ]);
    }
}
