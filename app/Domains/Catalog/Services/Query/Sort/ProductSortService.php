<?php

namespace App\Domains\Catalog\Services\Query\Sort;

use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Components\Queryable\Abstracts\QueryService;
use App\Domains\Components\Queryable\Classes\Sort\Sort;
use App\Domains\Components\Queryable\Enums\QueryKey;
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
