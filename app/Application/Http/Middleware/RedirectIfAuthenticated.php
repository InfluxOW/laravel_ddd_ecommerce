<?php

namespace App\Application\Http\Middleware;

use App\Application\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ?string ...$guards): mixed
    {
        $guards = $guards === [] ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
