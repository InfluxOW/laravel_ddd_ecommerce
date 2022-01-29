<?php

namespace App\Domains\Catalog\Http\Resources;

use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class LightProductCategoryResource extends JsonResource
{
    #[ArrayShape(['slug' => "string", 'title' => "string", 'description' => "string"])]
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;

        return [
            'slug' => $category->slug,
            'title' => $category->title,
            'description' => $category->description,
        ];
    }
}
