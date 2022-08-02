<?php

namespace App\Components\Queryable\Http\Resources\Sort;

use App\Components\Queryable\Classes\Sort\Sort;
use App\Components\Queryable\DTOs\Sort\SortQuery;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class SortQueryResource extends JsonResource
{
    #[ArrayShape(['applied' => 'array', 'allowed' => 'array'])]
    public function toArray($request): array
    {
        /** @var SortQuery $sortQuery */
        $sortQuery = $this->resource;

        return [
            'applied' => $sortQuery->appliedSort->toArray(),
            'allowed' => $sortQuery->allowedSorts->map(fn (Sort $sort): array => $sort->toArray())->toArray(),
        ];
    }
}
