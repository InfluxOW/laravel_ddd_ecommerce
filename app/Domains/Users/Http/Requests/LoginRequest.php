<?php

namespace App\Domains\Users\Http\Requests;

use App\Infrastructure\Abstracts\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }
}
