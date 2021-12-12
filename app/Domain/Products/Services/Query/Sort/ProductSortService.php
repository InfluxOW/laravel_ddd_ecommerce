<?php

namespace App\Domain\Products\Services\Query\Sort;

use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Generic\Query\Interfaces\QueryService;
use App\Domain\Generic\Query\Models\Sort\Sort;
use App\Domain\Products\Enums\Query\Sort\ProductAllowedSort;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductSortService implements QueryService
{
    public function getAllowed(): Collection
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

    public function getApplied(Request $request): ?Sort
    {
        /** @var string $sortQuery */
        $sortQuery = $request->query(QueryKey::SORT->value);

        return $this->getAllowed()->filter(static fn (Sort $sort): bool => ($sort->query === $sortQuery))->first();
    }
}
