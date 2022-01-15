<?php

namespace App\Domains\Catalog\Http\Requests;

use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Domains\Components\Queryable\Enums\QueryKey;
use App\Infrastructure\Abstracts\FormRequest;
use Illuminate\Validation\Rule;

class ProductShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CURRENCY->value) => ['nullable', 'string', Rule::in(app(CatalogSettings::class)->available_currencies)],
        ];
    }
}
