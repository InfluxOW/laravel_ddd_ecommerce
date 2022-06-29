<?php

namespace App\Domains\Generic\Http\Middleware;

use App\Domains\Admin\Models\Admin;
use App\Domains\Users\Models\User;
use Closure;
use Illuminate\Http\Request;
use Sentry\SentrySdk;
use Sentry\State\Scope;

final class AddUserToSentryScope
{
    public function handle(Request $request, Closure $next, string $guard): mixed
    {
        /** @var User|Admin|null $user */
        $user = $request->user($guard);

        if (isset($user)) {
            SentrySdk::getCurrentHub()->configureScope(function (Scope $scope) use ($user): void {
                $scope->setUser([
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'type' => strtolower(class_basename($user)),
                ]);
            });
        }

        return $next($request);
    }
}
