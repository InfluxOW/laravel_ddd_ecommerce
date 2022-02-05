<?php

namespace App\Domains\Catalog\Http\Resources\Query\Sort;

use App\Components\Queryable\Classes\Sort\Sort;
use App\Domains\Catalog\Classes\Query\Sort\ProductSortQuery;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class ProductSortQueryResource extends JsonResource
{
    #[ArrayShape(['applied' => "array", 'allowed' => "array"])]
    public function toArray($request): array
    {
        /** @var ProductSortQuery $sortQuery */
        $sortQuery = $this->resource;

        return [
            'applied' => $sortQuery->appliedSort->toArray(),
            'allowed' => $sortQuery->allowedSorts->map(fn (Sort $sort): array => $sort->toArray())->toArray(),
        ];
    }
}
