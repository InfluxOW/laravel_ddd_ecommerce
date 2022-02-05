<?php

namespace App\Domains\Catalog\Http\Resources\Query\Filter;

use App\Components\Queryable\Classes\Filter\Filter;
use App\Domains\Catalog\Classes\Query\Filter\ProductFilterQuery;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class ProductFilterQueryResource extends JsonResource
{
    #[ArrayShape(['applied' => "array", 'allowed' => "array"])]
    public function toArray($request): array
    {
        /** @var ProductFilterQuery $filterQuery */
        $filterQuery = $this->resource;

        return [
            'applied' => $filterQuery->appliedFilters->map(fn (Filter $filter): array => $filter->toArray())->toArray(),
            'allowed' => $filterQuery->allowedFilters->map(fn (Filter $filter): array => $filter->toArray())->toArray(),
        ];
    }
}
