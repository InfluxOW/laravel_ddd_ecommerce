<?php

namespace App\Domains\Admin\Admin\Resources\Development\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;

final class TelescopeLink extends ListRecords
{
    public function __invoke(Container $container, Route $route): RedirectResponse
    {
        return redirect()->route('telescope');
    }
}
