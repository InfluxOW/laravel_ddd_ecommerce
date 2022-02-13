<?php

namespace App\Infrastructure\Abstracts\Http;

use Illuminate\Foundation\Http\FormRequest as IlluminateFormRequest;

abstract class FormRequest extends IlluminateFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return array_combine(array_keys($this->rules()), array_keys($this->rules()));
    }

    abstract public function rules(): array;
}
