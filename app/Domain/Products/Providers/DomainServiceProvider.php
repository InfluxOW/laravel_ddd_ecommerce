<?php

namespace App\Domain\Products\Providers;

use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::PRODUCTS;

    protected bool $hasMigrations = true;

    protected bool $hasTranslations = true;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
