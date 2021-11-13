<?php

namespace App\Http\Resources;

use App\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductCategory **/
        $category = $this->resource;

        return [
            'title' => $category->title,
            'slug' => $category->slug,
            'children' => $this->whenLoaded(
                'children',
                $this->when(
                    $category->children->count() > 0,
                    self::collection($category->children)
                )
            ),
        ];
    }
}
