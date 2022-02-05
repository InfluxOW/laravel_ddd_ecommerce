<?php

namespace App\Domains\Catalog\Http\Requests;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Infrastructure\Abstracts\FormRequest;
use Illuminate\Validation\Rule;

final class ProductShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CURRENCY->name) => ['nullable', 'string', Rule::in(app(CatalogSettings::class)->available_currencies)],
        ];
    }
}
