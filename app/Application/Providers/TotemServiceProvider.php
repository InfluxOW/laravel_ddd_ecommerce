<?php

namespace App\Application\Providers;

use Illuminate\Support\Facades\Auth;
use Studio\Totem\Providers\TotemServiceProvider as BaseTotemServiceProvider;
use Studio\Totem\Totem;
use Studio\Totem\TotemModel;

final class TotemServiceProvider extends BaseTotemServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Totem::auth(static fn (): bool => Auth::guard('admin')->check());
    }

    public function preventLazyLoading(): void
    {
        TotemModel::preventLazyLoading(false);
    }
}
