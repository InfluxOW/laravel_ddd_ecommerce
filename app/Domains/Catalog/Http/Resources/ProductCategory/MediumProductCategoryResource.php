<?php

namespace App\Domains\Catalog\Http\Resources\ProductCategory;

use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;

final class MediumProductCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;

        return array_merge(LightProductCategoryResource::make($category)->toArray($request), [
            'description' => $category->description,
            /* @phpstan-ignore-next-line */
            'parent' => $this->when(isset($category->parent_id), fn (): JsonResource => self::make(ProductCategory::findInHierarchy($category->parent_id, ProductCategory::getHierarchy()))),
        ]);
    }
}
