<?php

namespace App\Application\Http\Middleware;

use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Domains\Components\Queryable\Enums\QueryKey;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SetDefaultCurrency
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        /**
         * @var array $filters
         * @phpstan-ignore-next-line
         */
        $filters = $request->request->get(QueryKey::FILTER->value, []);

        $request->request->set(QueryKey::FILTER->value, array_merge([
            ProductAllowedFilter::CURRENCY->value => app(CatalogSettings::class)->default_currency,
        ], $filters));

        return $next($request);
    }
}
