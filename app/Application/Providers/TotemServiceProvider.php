<?php

namespace App\Application\Providers;

use Illuminate\Support\Facades\Auth;
use Studio\Totem\Providers\TotemServiceProvider as BaseTotemServiceProvider;
use Studio\Totem\Totem;

class TotemServiceProvider extends BaseTotemServiceProvider
{
    public function boot()
    {
        parent::boot();

        Totem::auth(static fn (): bool => Auth::guard('admin')->check());
    }
}
