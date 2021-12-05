<?php

namespace App\Domain\Products\Http\Controllers\Api;

use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Generic\Query\Http\Resources\QueryServiceResource;
use App\Domain\Generic\Query\Models\Sort\Sort;
use App\Domain\Products\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Products\Enums\Query\Sort\ProductAllowedSort;
use App\Domain\Products\Http\Requests\ProductIndexRequest;
use App\Domain\Products\Http\Resources\ProductResource;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Services\Query\Filter\ProductFilterService;
use App\Domain\Products\Services\Query\Sort\ProductSortService;
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

    public function index(ProductIndexRequest $request, ProductFilterService $filterService, ProductSortService $sortService): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $allowedSorts = $sortService->getAllowed();
        /** @var Sort $defaultSort */
        $defaultSort = $allowedSorts->first();
        $appliedSort = $sortService->getApplied($request) ?? $defaultSort;
        $sortQueryServiceResource = new QueryServiceResource(QueryKey::SORT, false, $appliedSort->toArray(), $allowedSorts->map->toArray()->toArray());

        $productsQueryBase = QueryBuilder::for(Product::query()->with(['category', 'attributeValues.attribute']));
        $productsQuery = $productsQueryBase->clone()
            ->allowedFilters([
                ProductAllowedFilter::TITLE->value,
                ProductAllowedFilter::DESCRIPTION->value,
                AllowedFilter::callback(ProductAllowedFilter::CATEGORY->value, static fn (Builder|Product $query): Builder => $query->whereInCategory($validated['filter'][ProductAllowedFilter::CATEGORY->value])),
                AllowedFilter::callback(ProductAllowedFilter::PRICE_BETWEEN->value, static fn (Builder|Product $query): Builder => $query->wherePriceBetween(...$validated['filter'][ProductAllowedFilter::PRICE_BETWEEN->value])),
                AllowedFilter::callback(ProductAllowedFilter::ATTRIBUTE_VALUE->value, static fn (Builder|Product $query): Builder => $query->whereHasAttributeValue($validated['filter'][ProductAllowedFilter::ATTRIBUTE_VALUE->value])),
            ])
            ->allowedSorts([
                ProductAllowedSort::TITLE->value,
                ProductAllowedSort::CREATED_AT->value,
                /* @phpstan-ignore-next-line */
                AllowedSort::callback(ProductAllowedSort::PRICE->value, static fn (Builder|Product $query): Builder => $query->orderByCurrentPrice(AllowedSort::parseSortDirection($validated['sort']))),
            ])
            ->defaultSort($defaultSort->query);

        $allowedFilters = $filterService->setProductsQuery($productsQuery->clone())->getAllowed();
        $appliedFilters = $filterService->setProductsQuery($productsQueryBase->clone())->getApplied($request);
        $filterQueryServiceResource = new QueryServiceResource(QueryKey::FILTER, true, $appliedFilters->map->toArray()->toArray(), $allowedFilters->map->toArray()->toArray());

        $products = $productsQuery
            ->paginate($request->per_page ?? self::DEFAULT_ITEMS_PER_PAGE)
            ->appends($request->query() ?? []);

        return ProductResource::collection($products)->additional([
            'query' => [
                $sortQueryServiceResource->toArray(),
                $filterQueryServiceResource->toArray(),
            ],
        ]);
    }

    public function show(Product $product): JsonResource
    {
        return ProductResource::make($product);
    }
}
