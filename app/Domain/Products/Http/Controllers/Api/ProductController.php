<?php

namespace App\Domain\Products\Http\Controllers\Api;

use App\Domain\Products\Enums\ProductAllowedFilter;
use App\Domain\Products\Enums\ProductAllowedSort;
use App\Domain\Products\Http\Requests\ProductIndexRequest;
use App\Domain\Products\Http\Resources\ProductResource;
use App\Domain\Products\Http\Services\ProductFilterService;
use App\Domain\Products\Models\Product;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    private const DEFAULT_ITEMS_PER_PAGE = 20;

    public function index(ProductIndexRequest $request, ProductFilterService $filterService): AnonymousResourceCollection
    {
        $defaultSort = ProductAllowedSort::CREATED_AT->descendingValue();

        $productsQuery = QueryBuilder::for(Product::query()->with(['category', 'attributeValues.attribute']))
            ->allowedFilters([
                ProductAllowedFilter::TITLE->value,
                ProductAllowedFilter::DESCRIPTION->value,
                AllowedFilter::callback(ProductAllowedFilter::CATEGORY->value, static fn (Builder|Product $query): Builder => $query->whereInCategory($request->filter[ProductAllowedFilter::CATEGORY->value])),
                AllowedFilter::callback(ProductAllowedFilter::PRICE_BETWEEN->value, static fn (Builder|Product $query): Builder => $query->wherePriceBetween(...$request->filter[ProductAllowedFilter::PRICE_BETWEEN->value])),
                AllowedFilter::callback(ProductAllowedFilter::ATTRIBUTE->value, static fn (Builder|Product $query): Builder => $query->whereHasAttributeValue($request->filter[ProductAllowedFilter::ATTRIBUTE->value])),
            ])
            ->allowedSorts([
                ProductAllowedSort::TITLE->value,
                ProductAllowedSort::CREATED_AT->value,
                /* @phpstan-ignore-next-line */
                AllowedSort::callback(ProductAllowedSort::PRICE->value, static fn (Builder|Product $query, bool $descending): Builder => $query->orderByCurrentPrice($descending)),
            ])
            ->defaultSort($defaultSort);

        $products = $productsQuery->clone()
            ->paginate($request->per_page ?? self::DEFAULT_ITEMS_PER_PAGE)
            ->appends($request->query());

        $additional = array_merge(
            $filterService->getAvailableFilters($productsQuery),
            $filterService->getAppliedFilters($request, $defaultSort),
        );

        return ProductResource::collection($products)->additional($additional);
    }

    public function show(Product $product): JsonResource
    {
        return ProductResource::make($product);
    }
}
