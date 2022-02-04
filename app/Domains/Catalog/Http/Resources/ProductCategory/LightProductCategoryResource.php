<?php

namespace App\Domains\Catalog\Http\Resources\ProductCategory;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;

final class LightProductCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;

        return [
            'slug' => $category->slug,
            'title' => $category->title,
            'url' => route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::CATEGORY->value => $category->slug]]),
        ];
    }
}
