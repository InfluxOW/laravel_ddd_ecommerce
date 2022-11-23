<?php

namespace App\Domains\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
