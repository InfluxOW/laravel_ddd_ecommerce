<?php

namespace App\Domains\Catalog\Services\Query\Sort;

use App\Components\Queryable\Abstracts\QueryService;
use App\Components\Queryable\Classes\Sort\Sort;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class ProductSortService implements QueryService
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
