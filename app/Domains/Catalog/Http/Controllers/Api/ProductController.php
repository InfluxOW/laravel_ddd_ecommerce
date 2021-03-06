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
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductController extends Controller
{
    public function index(ProductIndexRequest $request, ProductFilterService $filterService, ProductSortService $sortService): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $currency = $validated[QueryKey::FILTER->value][ProductAllowedFilter::CURRENCY->name];

        $query = QueryBuilder::for(self::getBaseProductsQuery($currency)->with(['image.model']));

        $filterService->prepare($validated, $query->clone());
        $sortService->prepare($currency);

        return $this->respondPaginated(LightProductResource::class, $query, $request, $filterService, $sortService);
    }

    public function show(ProductShowRequest $request, string $slug): JsonResource|JsonResponse
    {
        $query = self::getBaseProductsQuery($request->validated()[QueryKey::FILTER->value][ProductAllowedFilter::CURRENCY->name])
            ->with(['attributeValues.attribute', 'images.model'])
            ->where('slug', $slug);

        return $this->respondWithPossiblyNotFoundItem(HeavyProductResource::class, $query);
    }

    private static function getBaseProductsQuery(string $currency): Builder
    {
        return Product::query()
            ->select(['products.*'])
            ->displayable()
            ->with([
                /** @phpstan-ignore-next-line */
                'categories' => fn (BelongsToMany|ProductCategory $query): BelongsToMany => $query->displayable(),
                'prices' => fn (MorphMany $query): MorphMany => $query->where('currency', $currency),
            ]);
    }
}
