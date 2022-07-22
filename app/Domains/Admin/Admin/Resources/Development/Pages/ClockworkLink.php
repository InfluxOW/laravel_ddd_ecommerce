<?php

namespace App\Domains\Admin\Admin\Resources\Development\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use Clockwork\Support\Laravel\ClockworkController;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;

final class ClockworkLink extends ListRecords
{
    public function __invoke(Container $container, Route $route): RedirectResponse
    {
        return redirect()->action([ClockworkController::class, 'webIndex']);
    }
}
