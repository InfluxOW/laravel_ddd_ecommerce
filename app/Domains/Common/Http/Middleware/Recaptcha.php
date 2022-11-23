<?php

namespace App\Domains\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

final class Recaptcha
{
    public function handle(Request $request, Closure $next): mixed
    {
        $validator = Validator::make($request->input(), [
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->passes()) {
            return $next($request);
        }

        return response($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
