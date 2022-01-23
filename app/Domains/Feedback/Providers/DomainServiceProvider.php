<?php

namespace App\Domains\Feedback\Providers;

use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Policies\FeedbackPolicy;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::FEEDBACK;

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
