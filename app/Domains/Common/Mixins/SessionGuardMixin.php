<?php

namespace App\Domains\Common\Mixins;

use Closure;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @mixin SessionGuard
 * */
final class SessionGuardMixin
{
    public function retrieveUserByCredentials(): Closure
    {
        return fn (array $credentials): ?Authenticatable => $this->getProvider()->retrieveByCredentials($credentials);
    }
}
