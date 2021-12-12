<?php

namespace App\Domain\Products\Http\Requests;

use App\Domain\Generic\Currency\Models\Kopecks;
use App\Domain\Generic\Query\Enums\QueryKey;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'int', 'min:1', 'max:100'],
            QueryKey::FILTER->value . '.title' => ['nullable', 'string'],
            QueryKey::FILTER->value . '.description' => ['nullable', 'string'],
            QueryKey::FILTER->value . '.category' => ['nullable', 'array'],
            QueryKey::FILTER->value . '.category.*' => ['required', 'string'],
            QueryKey::FILTER->value . '.price_between' => ['nullable', 'array', 'size:2'],
            QueryKey::FILTER->value . '.price_between.*' => ['nullable', 'numeric', 'min:0.01'],
            QueryKey::FILTER->value . '.attribute.*' => ['nullable', 'array'],
            QueryKey::SORT->value => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        if (isset($this->filter)) {
            $this->merge([
                QueryKey::FILTER->value => array_merge(
                    $this->filter,
                    array_key_exists('category', $this->filter) ? ['category' => explode(',', $this->filter['category'])] : [],
                    array_key_exists('price_between', $this->filter) ? ['price_between' => array_map(static fn (string $value): ?int => ($value === '') ? null : (int)(round($value * Kopecks::KOPECKS_IN_ROUBLE)), explode(',', $this->filter['price_between']))] : [],
                    array_key_exists('attribute', $this->filter) ? ['attribute' => array_map(static fn (string $value): array => explode(',', $value), (array) $this->filter['attribute'])] : [],
                ),
            ]);
        }

        if (isset($this->sort)) {
            $this->merge([
               QueryKey::SORT->value => str_starts_with($this->sort, '-') ? '-' . ltrim($this->sort, '-') : $this->sort,
            ]);
        }
    }

    public function attributes(): array
    {
        return array_combine(array_keys($this->rules()), array_keys($this->rules()));
    }
}
