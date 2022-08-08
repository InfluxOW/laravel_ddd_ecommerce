<?php

namespace App\Domains\Catalog\Services\Query\Sort;

use App\Components\Queryable\Abstracts\Sort\SortService;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domains\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final class ProductSortService extends SortService
{
    private string $currency;

    public function prepare(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function build(): static
    {
        return $this
            ->addDefaultSearchSort(ProductAllowedSort::DEFAULT, static fn (Builder $query): Builder => $query)
            ->addSort(ProductAllowedSort::TITLE)
            ->addSort(ProductAllowedSort::TITLE_DESC)
            ->addSort(ProductAllowedSort::CREATED_AT)
            ->addDefaultSort(ProductAllowedSort::CREATED_AT_DESC)
            /** @phpstan-ignore-next-line */
            ->addSort(ProductAllowedSort::PRICE, fn (Builder|Product $query): Builder => $query->reorder()->orderByCurrentPrice($this->currency, descending: false))
            /** @phpstan-ignore-next-line */
            ->addSort(ProductAllowedSort::PRICE_DESC, fn (Builder|Product $query): Builder => $query->reorder()->orderByCurrentPrice($this->currency, descending: true));
    }
}
