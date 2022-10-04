<?php

namespace App\Domains\Catalog\Http\Resources\ProductCategory;

use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use Stevebauman\Purify\Facades\Purify;

final class MediumProductCategoryResource extends JsonResource
{
    #[ArrayShape(['slug' => 'string', 'title' => 'string', 'url' => 'string', 'description' => 'string', 'parent' => self::class . '|' . 'optional'])]
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;
        $description = $category->description;

        return array_merge(LightProductCategoryResource::make($category)->toArray($request), [
            'description' => isset($description) ? Purify::clean($description) : null,
            /* @phpstan-ignore-next-line */
            'parent' => $this->when(isset($category->parent_id), fn (): MediumProductCategoryResource => self::make(ProductCategory::findInHierarchy($category->parent_id, ProductCategory::getHierarchy()))),
        ]);
    }
}
