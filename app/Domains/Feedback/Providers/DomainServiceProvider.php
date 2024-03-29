<?php

namespace App\Domains\Feedback\Providers;

use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Policies\FeedbackPolicy;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::FEEDBACK;

    protected array $policies = [
        Feedback::class => FeedbackPolicy::class,
    ];

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
