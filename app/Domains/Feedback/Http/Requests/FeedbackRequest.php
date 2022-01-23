<?php

namespace App\Domains\Feedback\Http\Requests;

use App\Infrastructure\Abstracts\FormRequest;
use Illuminate\Validation\Rule;

class FeedbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => [Rule::requiredIf(fn (): bool => ($this->user() === null)), 'string', 'max:255', 'min:3'],
            'email' => [Rule::requiredIf(fn (): bool => ($this->user() === null)), 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'min:12', 'max:12', 'regex:/^\+[\d]{11}$/'],
            'text' => ['required', 'string'],
        ];
    }
}
