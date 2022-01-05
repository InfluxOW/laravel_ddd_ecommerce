<?php

namespace App\Domain\Catalog\Http\Controllers\Api;

use App\Domain\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domain\Catalog\Http\Requests\ProductIndexRequest;
use App\Domain\Catalog\Http\Requests\ProductShowRequest;
use App\Domain\Catalog\Http\Resources\ProductResource;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductCategory;
use App\Domain\Catalog\Services\Query\Filter\ProductFilterService;
use App\Domain\Catalog\Services\Query\Sort\ProductSortService;
use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Generic\Query\Http\Resources\QueryServiceResource;
use App\Domain\Generic\Query\Models\Sort\Sort;
use App\Domain\Generic\Response\Enums\ResponseKey;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    protected string $resource = ProductResource::class;

    private const DEFAULT_ITEMS_PER_PAGE = 20;

    public function index(ProductIndexRequest $request, ProductFilterService $filterService, ProductSortService $sortService): AnonymousResourceCollection
    {
        ProductCategory::loadLightHierarchy();

        $validated = $request->validated();

        $currency = $validated[QueryKey::FILTER->value][ProductAllowedFilter::CURRENCY->value];
        $filterService->setCurrency($currency);

        $allowedSorts = $sortService->getAllowed();
        /** @var Sort $defaultSort */
        $defaultSort = $allowedSorts->first();
        $appliedSort = $sortService->getApplied($request) ?? $defaultSort;
        $sortQueryServiceResource = new QueryServiceResource(QueryKey::SORT, false, [$appliedSort->toArray()], $allowedSorts->map->toArray()->toArray());

        $productsQueryBase = QueryBuilder::for($this->getBaseProductQuery($currency));
        $productsQuery = $productsQueryBase->clone()
            ->allowedFilters([
                ProductAllowedFilter::TITLE->value,
                ProductAllowedFilter::DESCRIPTION->value,
                AllowedFilter::callback(ProductAllowedFilter::CURRENCY->value, static fn (Builder|Product $query): Builder => $query->whereHasPriceCurrency($currency)),
                AllowedFilter::callback(ProductAllowedFilter::CATEGORY->value, static fn (Builder|Product $query): Builder => $query->whereInCategory(ProductCategory::query()->visible()->hasLimitedDepth()->whereIn('slug', $validated[QueryKey::FILTER->value][ProductAllowedFilter::CATEGORY->value])->get())),
                AllowedFilter::callback(ProductAllowedFilter::PRICE_BETWEEN->value, static fn (Builder|Product $query): Builder => $query->wherePriceBetween($currency, ...$validated[QueryKey::FILTER->value][ProductAllowedFilter::PRICE_BETWEEN->value])),
                AllowedFilter::callback(ProductAllowedFilter::ATTRIBUTE_VALUE->value, static fn (Builder|Product $query): Builder => $query->whereHasAttributeValue($validated[QueryKey::FILTER->value][ProductAllowedFilter::ATTRIBUTE_VALUE->value])),
            ])
            ->allowedSorts([
                ProductAllowedSort::TITLE->value,
                ProductAllowedSort::CREATED_AT->value,
                /** @phpstan-ignore-next-line */
                AllowedSort::callback(ProductAllowedSort::PRICE->value, static fn (Builder|Product $query, bool $descending): Builder => $query->orderByCurrentPrice($currency, $descending)),
            ])
            ->defaultSort($defaultSort->query);

        $allowedFilters = $filterService->setProductsQuery($productsQuery->clone())->getAllowed();
        $appliedFilters = $filterService->setProductsQuery($productsQueryBase->clone())->getApplied($request);
        $filterQueryServiceResource = new QueryServiceResource(QueryKey::FILTER, true, $appliedFilters->map->toArray()->toArray(), $allowedFilters->map->toArray()->toArray());

        $products = $productsQuery
            ->paginate($validated[QueryKey::PER_PAGE->value] ?? self::DEFAULT_ITEMS_PER_PAGE, ['*'], QueryKey::PAGE->value, $validated[QueryKey::PAGE->value] ?? 1)
            ->appends($request->query() ?? []);

        return $this->respondWithCollection($products, [
            ResponseKey::QUERY->value => [
                QueryKey::SORT->value => $sortQueryServiceResource->toArray(),
                QueryKey::FILTER->value => $filterQueryServiceResource->toArray(),
            ],
        ]);
    }

    public function show(ProductShowRequest $request, string $slug): JsonResource|JsonResponse
    {
        ProductCategory::loadLightHierarchy();

        $product = $this->getBaseProductQuery($request->validated()[QueryKey::FILTER->value][ProductAllowedFilter::CURRENCY->value])->where('slug', $slug)->first();

        if ($product === null) {
            return $this->respondWithMessage("Product '{$slug}' was not found.", Response::HTTP_NOT_FOUND);
        }

        return $this->respondWithItem($product);
    }

    private function getBaseProductQuery(string $currency): Builder
    {
        return Product::query()
            /** @phpstan-ignore-next-line */
            ->whereHas('categories', fn (Builder|ProductCategory $query): Builder => $query->visible())
            ->with([
                /** @phpstan-ignore-next-line */
                'categories' => fn (BelongsToMany|ProductCategory $query): BelongsToMany => $query->visible(),
                'attributeValues.attribute',
                'prices' => fn (HasMany $query): HasMany => $query->where('currency', $currency),
            ]);
    }
}
