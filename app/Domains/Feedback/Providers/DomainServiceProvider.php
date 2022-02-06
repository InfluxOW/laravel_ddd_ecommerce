<?php

namespace App\Domains\Feedback\Providers;

use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Policies\FeedbackPolicy;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::FEEDBACK;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
    protected bool $hasPolicies = true;

    protected array $policies = [
        Feedback::class => FeedbackPolicy::class,
    ];

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
