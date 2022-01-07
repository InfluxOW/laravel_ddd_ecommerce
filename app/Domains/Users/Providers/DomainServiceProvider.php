<?php

namespace App\Domains\Users\Providers;

use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::USERS;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;

    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
    ];
}
