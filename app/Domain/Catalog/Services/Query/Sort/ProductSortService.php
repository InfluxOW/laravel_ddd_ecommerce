<?php

namespace App\Domain\Catalog\Services\Query\Sort;

use App\Domain\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domain\Catalog\Providers\DomainServiceProvider;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Generic\Query\Interfaces\QueryService;
use App\Domain\Generic\Query\Models\Sort\Sort;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductSortService implements QueryService
{
    private readonly TranslationNamespace $namespace;

    public function __construct()
    {
        $this->namespace = DomainServiceProvider::TRANSLATION_NAMESPACE;
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
