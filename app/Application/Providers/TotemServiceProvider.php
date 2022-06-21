<?php

namespace App\Application\Providers;

use Illuminate\Support\Facades\Auth;
use Studio\Totem\Providers\TotemServiceProvider as BaseTotemServiceProvider;
use Studio\Totem\Totem;
use Studio\Totem\TotemModel;

class TotemServiceProvider extends BaseTotemServiceProvider
{
    public function boot()
    {
        parent::boot();

        Totem::auth(static fn (): bool => Auth::guard('admin')->check());
    }

    public function preventLazyLoading(): void
    {
        TotemModel::preventLazyLoading(false);
    }
}
