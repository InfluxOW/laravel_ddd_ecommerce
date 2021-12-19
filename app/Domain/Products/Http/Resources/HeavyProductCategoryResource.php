<?php

namespace App\Domain\Products\Http\Resources;

use App\Domain\Products\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class HeavyProductCategoryResource extends JsonResource
{
    #[ArrayShape(['slug' => "string", 'title' => "string", 'products_count' => "int", 'children' => "Illuminate\Http\Resources\Json\AnonymousResourceCollection|optional"])]
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;

        return array_merge(LightProductCategoryResource::make($this->resource)->toArray($request), [
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
