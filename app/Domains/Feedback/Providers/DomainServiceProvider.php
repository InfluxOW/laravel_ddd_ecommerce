<?php

namespace App\Domains\Feedback\Providers;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Policies\FeedbackPolicy;
use App\Infrastructure\Abstracts\ServiceProviderBase;

final class DomainServiceProvider extends ServiceProviderBase
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
