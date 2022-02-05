<?php

namespace App\Application\Http\Middleware;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SetDefaultCurrency
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        /**
         * @var array $filters
         * @phpstan-ignore-next-line
         */
        $filters = $request->request->get(QueryKey::FILTER->value, []);

        $request->request->set(QueryKey::FILTER->value, array_merge([
            ProductAllowedFilter::CURRENCY->name => app(CatalogSettings::class)->default_currency,
        ], $filters));

        return $next($request);
    }
}
