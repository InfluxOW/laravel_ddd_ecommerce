<?php

namespace App\Domain\Products\Http\Requests;

use App\Domain\Products\Models\Generic\Kopecks;
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
            'filter.title' => ['nullable', 'string'],
            'filter.description' => ['nullable', 'string'],
            'filter.category' => ['nullable', 'array'],
            'filter.category.*' => ['required', 'string'],
            'filter.price_between' => ['nullable', 'array', 'size:2'],
            'filter.price_between.*' => ['nullable', 'numeric', 'min:0.01'],
            'filter.attribute.*' => ['nullable', 'array'],
            'sort' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        if (isset($this->filter)) {
            $this->merge([
                'filter' => array_merge(
                    $this->filter,
                    array_key_exists('category', $this->filter) ? ['category' => explode(',', $this->filter['category'])] : [],
                    array_key_exists('price_between', $this->filter) ? ['price_between' => array_map(static fn (string $value) => ($value === '') ? null : (int)(round($value * Kopecks::KOPECKS_IN_ROUBLE)), explode(',', $this->filter['price_between']))] : [],
                    array_key_exists('attribute', $this->filter) ? ['attribute' => array_map(static fn (string $value) => explode(',', $value), $this->filter['attribute'])] : [],
                ),
            ]);
        }

        if (isset($this->sort)) {
            $this->merge([
               'sort' => str_starts_with($this->sort, '-') ? '-' . ltrim($this->sort, '-') : $this->sort,
            ]);
        }
    }
}
