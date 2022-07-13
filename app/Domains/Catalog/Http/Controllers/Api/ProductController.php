<?php

namespace App\Domains\Catalog\Http\Controllers\Api;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Http\Requests\ProductIndexRequest;
use App\Domains\Catalog\Http\Requests\ProductShowRequest;
use App\Domains\Catalog\Http\Resources\Product\HeavyProductResource;
use App\Domains\Catalog\Http\Resources\Product\LightProductResource;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Services\Query\Filter\ProductFilterService;
use App\Domains\Catalog\Services\Query\Sort\ProductSortService;
use App\Domains\Generic\Enums\Response\ResponseKey;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductController extends Controller
{
    public function index(ProductIndexRequest $request, ProductFilterService $filterService, ProductSortService $sortService): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $currency = $validated[QueryKey::FILTER->value][ProductAllowedFilter::CURRENCY->name];

        $allowedQuerySorts = $sortService->getAllowedSortsForQuery($currency);

        $productsQuery = QueryBuilder::for($this->getBaseProductsQuery($currency)->with(['image.model']))
            ->allowedFilters($filterService->getAllowedFiltersForQuery($validated))
            ->allowedSorts($allowedQuerySorts)
            ->defaultSort(Arr::first($allowedQuerySorts));

        $filterService->setCurrency($currency)->setProductsQuery($productsQuery->clone());

        $products = $productsQuery
            ->paginate($validated[QueryKey::PER_PAGE->value], ['*'], QueryKey::PAGE->value, $validated[QueryKey::PAGE->value])
            ->appends($request->query() ?? []);

        return $this->respondWithCollection(LightProductResource::class, $products, [
            ResponseKey::QUERY->value => [
                QueryKey::SORT->value => $sortService->toResource($request),
                QueryKey::FILTER->value => $filterService->toResource($request),
            ],
        ]);
    }

    public function show(ProductShowRequest $request, string $slug): JsonResource|JsonResponse
    {
        $product = $this->getBaseProductsQuery($request->validated()[QueryKey::FILTER->value][ProductAllowedFilter::CURRENCY->name])
            ->with(['attributeValues.attribute', 'images.model'])
            ->where('slug', $slug)
            ->first();

        return ($product === null) ? $this->respondNotFound() : $this->respondWithItem(HeavyProductResource::class, $product);
    }

    private function getBaseProductsQuery(string $currency): Builder
    {
        return Product::query()
            ->select(['products.*'])
            ->displayable()
            ->with([
                /** @phpstan-ignore-next-line */
                'categories' => fn (BelongsToMany|ProductCategory $query): BelongsToMany => $query->displayable(),
                'prices' => fn (HasMany $query): HasMany => $query->where('currency', $currency),
            ]);
    }
}
