<?php

namespace App\Domain\Products\Services\Sorts;

use App\Domain\Products\Enums\Sorts\ProductAllowedSort;
use App\Domain\Products\Http\Requests\ProductIndexRequest;
use App\Domain\Products\Models\Generic\Sorts\Sort;
use Illuminate\Support\Collection;

class ProductSortService
{
    public function getAppliedSort(ProductIndexRequest $request): ?Sort
    {
        /** @var string $sortQuery */
        $sortQuery = $request->query('sort');

        return $this->getAllowedSorts()->filter(static fn (Sort $sort) => ($sort->query === $sortQuery))->first();
    }

    public function getAllowedSorts(): Collection
    {
        return collect([
            Sort::createAsc(ProductAllowedSort::TITLE),
            Sort::createDesc(ProductAllowedSort::TITLE),
            Sort::createAsc(ProductAllowedSort::PRICE),
            Sort::createDesc(ProductAllowedSort::PRICE),
            Sort::createAsc(ProductAllowedSort::CREATED_AT),
            Sort::createDesc(ProductAllowedSort::CREATED_AT),
        ]);
    }
}
