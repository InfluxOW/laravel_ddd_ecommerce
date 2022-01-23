<?php

namespace App\Domains\Cart\Providers;

use App\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::CART;

    protected bool $hasMigrations = true;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
