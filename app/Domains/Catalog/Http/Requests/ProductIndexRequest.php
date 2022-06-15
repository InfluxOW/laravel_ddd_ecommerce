<?php

namespace App\Domains\Catalog\Http\Requests;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Infrastructure\Abstracts\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ProductIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            QueryKey::PAGE->value => ['nullable', 'int', 'min:1'],
            QueryKey::PER_PAGE->value => ['nullable', 'int', 'min:1', 'max:100'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::SEARCH->name) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->name) => ['nullable', 'array'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->name) => ['required', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CURRENCY->name) => ['nullable', 'string', Rule::in(app(CatalogSettings::class)->available_currencies)],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->name) => ['nullable', 'array', 'size:2'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->name) => ['nullable', 'numeric', 'min:0.01'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::ATTRIBUTE_VALUE->name) => ['nullable', 'array'],
            QueryKey::SORT->value => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $filter = $this->filter;
        if (isset($filter)) {
            $this->merge([
                QueryKey::FILTER->value => array_merge(
                    $filter,
                    array_key_exists(ProductAllowedFilter::CATEGORY->name, $filter) ? [ProductAllowedFilter::CATEGORY->name => explode(',', is_array($filter[ProductAllowedFilter::CATEGORY->name]) ? implode(',', $filter[ProductAllowedFilter::CATEGORY->name]) : $filter[ProductAllowedFilter::CATEGORY->name])] : [],
                    array_key_exists(ProductAllowedFilter::PRICE_BETWEEN->name, $filter) ? [ProductAllowedFilter::PRICE_BETWEEN->name => array_map(static fn (string $value): ?int => ($value === '') ? null : (int) money($value, $filter[ProductAllowedFilter::CURRENCY->name], true)->getAmount(), explode(',', $filter[ProductAllowedFilter::PRICE_BETWEEN->name]))] : [],
                    array_key_exists(ProductAllowedFilter::ATTRIBUTE_VALUE->name, $filter) ? [ProductAllowedFilter::ATTRIBUTE_VALUE->name => array_map(static fn (string $value): array => explode(',', $value), (array) $filter[ProductAllowedFilter::ATTRIBUTE_VALUE->name])] : [],
                ),
            ]);
        }
    }
}
