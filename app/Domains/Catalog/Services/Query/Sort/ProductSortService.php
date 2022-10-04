<?php

namespace App\Domains\Catalog\Services\Query\Sort;

use App\Components\Queryable\Abstracts\Sort\SortService;
use App\Domains\Catalog\Database\Builders\ProductBuilder;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;

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
            ->addDefaultSearchSort(ProductAllowedSort::DEFAULT, static fn (ProductBuilder $query) => $query)
            ->addSort(ProductAllowedSort::TITLE)
            ->addSort(ProductAllowedSort::TITLE_DESC)
            ->addSort(ProductAllowedSort::CREATED_AT)
            ->addDefaultSort(ProductAllowedSort::CREATED_AT_DESC)
            ->addSort(ProductAllowedSort::PRICE, fn (ProductBuilder $query) => $query->reorder()->orderByCurrentPrice($this->currency, descending: false))
            ->addSort(ProductAllowedSort::PRICE_DESC, fn (ProductBuilder $query) => $query->reorder()->orderByCurrentPrice($this->currency, descending: true));
    }
}
