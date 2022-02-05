<?php

namespace App\Domains\Catalog\Services\Query\Sort;

use App\Components\Queryable\Abstracts\QueryService;
use App\Components\Queryable\Classes\Sort\Sort;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Classes\Query\Sort\ProductSortQuery;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domains\Catalog\Http\Resources\Query\Sort\ProductSortQueryResource;
use App\Domains\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedSort;

final class ProductSortService implements QueryService
{
    /**
     * @return Collection<Sort>
     */
    public function getAllowed(): Collection
    {
        return collect([
            Sort::create(ProductAllowedSort::TITLE),
            Sort::create(ProductAllowedSort::TITLE_DESC),
            Sort::create(ProductAllowedSort::CREATED_AT),
            Sort::create(ProductAllowedSort::CREATED_AT_DESC),
            Sort::create(ProductAllowedSort::PRICE),
            Sort::create(ProductAllowedSort::PRICE_DESC),
        ]);
    }

    /**
     * @return AllowedSort[]
     */
    public function getAllowedSortsForQuery(string $currency): array
    {
        return [
            AllowedSort::callback(ProductAllowedSort::TITLE->name, static fn (Builder $query): Builder => $query->orderBy(ProductAllowedSort::TITLE->value)),
            AllowedSort::callback(ProductAllowedSort::TITLE_DESC->name, static fn (Builder $query): Builder => $query->orderByDesc(ProductAllowedSort::TITLE->value)),
            AllowedSort::callback(ProductAllowedSort::CREATED_AT->name, static fn (Builder $query): Builder => $query->orderBy(ProductAllowedSort::CREATED_AT->value)),
            AllowedSort::callback(ProductAllowedSort::CREATED_AT_DESC->name, static fn (Builder $query): Builder => $query->orderByDesc(ProductAllowedSort::CREATED_AT->value)),
            /** @phpstan-ignore-next-line */
            AllowedSort::callback(ProductAllowedSort::PRICE->name, static fn (Builder|Product $query): Builder => $query->orderByCurrentPrice($currency, descending: false)),
            /** @phpstan-ignore-next-line */
            AllowedSort::callback(ProductAllowedSort::PRICE_DESC->name, static fn (Builder|Product $query): Builder => $query->orderByCurrentPrice($currency, descending: true)),
        ];
    }

    public function getApplied(Request $request): ?Sort
    {
        /** @var string $sortQuery */
        $sortQuery = $request->query(QueryKey::SORT->value);

        return $this->getAllowed()->filter(static fn (Sort $sort): bool => ($sort->query === $sortQuery))->first();
    }

    public function toResource(Request $request): JsonResource
    {
        $allowedSorts = $this->getAllowed();
        $appliedSort = $this->getApplied($request) ?? $allowedSorts->first();

        return ProductSortQueryResource::make(new ProductSortQuery($allowedSorts, $appliedSort));
    }
}
