<?php

namespace App\Domains\Users\Http\Requests;

use App\Infrastructure\Abstracts\Http\FormRequest;

final class EmailVerificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
        ];
    }
}
