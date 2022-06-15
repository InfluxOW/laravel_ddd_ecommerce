<?php

namespace App\Domains\Catalog\Http\Middleware;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Closure;
use Illuminate\Http\Request;

final class SetDefaultCurrency
{
    public function handle(Request $request, Closure $next): mixed
    {
        /**
         * @var array $filters
         */
        $filters = $request->offsetGet(QueryKey::FILTER->value) ?? [];

        $request->offsetSet(QueryKey::FILTER->value, array_merge([
            ProductAllowedFilter::CURRENCY->name => app(CatalogSettings::class)->default_currency,
        ], $filters));

        return $next($request);
    }
}
