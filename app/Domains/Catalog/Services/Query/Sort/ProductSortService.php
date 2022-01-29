<?php

namespace App\Domains\Catalog\Services\Query\Sort;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Components\Queryable\Abstracts\QueryService;
use App\Components\Queryable\Classes\Sort\Sort;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class ProductSortService implements QueryService
{
    private readonly ServiceProviderNamespace $namespace;

    public function __construct()
    {
        $this->namespace = DomainServiceProvider::NAMESPACE;
    }

    public function getAllowed(): Collection
    {
        return collect([
            Sort::createAsc(ProductAllowedSort::TITLE, $this->namespace),
            Sort::createDesc(ProductAllowedSort::TITLE, $this->namespace),
            Sort::createAsc(ProductAllowedSort::PRICE, $this->namespace),
            Sort::createDesc(ProductAllowedSort::PRICE, $this->namespace),
            Sort::createAsc(ProductAllowedSort::CREATED_AT, $this->namespace),
            Sort::createDesc(ProductAllowedSort::CREATED_AT, $this->namespace),
        ]);
    }

    public function getApplied(Request $request): ?Sort
    {
        /** @var string $sortQuery */
        $sortQuery = $request->query(QueryKey::SORT->value);

        return $this->getAllowed()->filter(static fn (Sort $sort): bool => ($sort->query === $sortQuery))->first();
    }
}
