<?php

namespace App\Domains\Admin\Admin\Resources\Development\SwaggerLinkResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;

final class RabbitMQLink extends ListRecords
{
    public function __invoke(Container $container, Route $route): RedirectResponse
    {
        $port = env('FORWARD_RABBITMQ_HTTP_PORT');

        return redirect()->to("http://0.0.0.0:{$port}");
    }
}
