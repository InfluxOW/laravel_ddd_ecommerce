<?php

namespace App\Domains\Catalog\Http\Controllers\Api;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Database\Builders\ProductBuilder;
use App\Domains\Catalog\Database\Builders\ProductCategoryBuilder;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Http\Requests\ProductIndexRequest;
use App\Domains\Catalog\Http\Requests\ProductShowRequest;
use App\Domains\Catalog\Http\Resources\Product\HeavyProductResource;
use App\Domains\Catalog\Http\Resources\Product\LightProductResource;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\Query\Filter\ProductFilterService;
use App\Domains\Catalog\Services\Query\Sort\ProductSortService;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated(QueryKey::FILTER->value, []);
        $currency = $filters[ProductAllowedFilter::CURRENCY->name];

        $query = QueryBuilder::for(self::getBaseQuery($currency)->with(['image.model']));

        return $this->respondPaginated(
            LightProductResource::class,
            $query,
            $request,
            app(ProductFilterService::class, ['filters' => $filters, 'query' => $query->clone()]),
            app(ProductSortService::class, ['currency' => $currency])
        );
    }

    public function show(ProductShowRequest $request, string $slug): JsonResource|JsonResponse
    {
        $query = self::getBaseQuery($request->validated(sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CURRENCY->name)))
            ->with(['attributeValues.attribute', 'images.model'])
            ->where('slug', $slug);

        return $this->respondWithPossiblyNotFoundItem(HeavyProductResource::class, $query);
    }

    private static function getBaseQuery(string $currency): ProductBuilder
    {
        return Product::query()
            ->select(['products.*'])
            ->displayable()
            ->with([
                /** @phpstan-ignore-next-line */
                'categories' => fn (BelongsToMany|ProductCategoryBuilder $query) => $query->displayable(),
                'prices' => fn (MorphMany $query) => $query->where('currency', $currency),
            ]);
    }
}
