<?php

namespace App\Domains\Users\Providers;

use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Domains\Common\Exceptions\InvalidConfirmationTokenException;
use App\Domains\Users\Admin\Components\Widgets\CustomersChartWidget;
use App\Domains\Users\Models\User;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use App\Interfaces\Http\Controllers\ResponseTrait;

final class DomainServiceProvider extends ServiceProvider
{
    use ResponseTrait;

    public const NAMESPACE = ServiceProviderNamespace::USERS;

    protected array $livewireComponents = [
        CustomersChartWidget::class,
    ];

    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
    ];

    protected array $morphMap = [
        'user' => User::class,
    ];

    protected function getCustomExceptionRenderers(): array
    {
        return [
            fn (InvalidConfirmationTokenException $e): mixed => $this->respondNotFound(),
        ];
    }
}
