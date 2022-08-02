<?php

namespace App\Components\Queryable\Http\Resources\Filter;

use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\DTOs\Filter\FilterQuery;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class FilterQueryResource extends JsonResource
{
    #[ArrayShape(['applied' => 'array', 'allowed' => 'array'])]
    public function toArray($request): array
    {
        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->resource;

        return [
            'applied' => $filterQuery->appliedFilters->map(fn (Filter $filter): array => $filter->applied())->toArray(),
            'allowed' => $filterQuery->allowedFilters->map(fn (Filter $filter): array => $filter->allowed())->toArray(),
        ];
    }
}
