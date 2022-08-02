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
            ->add(ProductAllowedSort::DEFAULT, static fn (Builder $query): Builder => $query)
            ->add(ProductAllowedSort::TITLE, static fn (Builder $query): Builder => $query->reorder()->orderBy(ProductAllowedSort::TITLE->getDatabaseField()))
            ->add(ProductAllowedSort::TITLE_DESC, static fn (Builder $query): Builder => $query->reorder()->orderByDesc(ProductAllowedSort::TITLE->getDatabaseField()))
            ->add(ProductAllowedSort::CREATED_AT, static fn (Builder $query): Builder => $query->reorder()->orderBy(ProductAllowedSort::CREATED_AT->getDatabaseField()))
            ->add(ProductAllowedSort::CREATED_AT_DESC, static fn (Builder $query): Builder => $query->reorder()->orderByDesc(ProductAllowedSort::CREATED_AT->getDatabaseField()))
            /** @phpstan-ignore-next-line */
            ->add(ProductAllowedSort::PRICE, fn (Builder|Product $query): Builder => $query->reorder()->orderByCurrentPrice($this->currency, descending: false))
            /** @phpstan-ignore-next-line */
            ->add(ProductAllowedSort::PRICE_DESC, fn (Builder|Product $query): Builder => $query->reorder()->orderByCurrentPrice($this->currency, descending: true));
    }
}
