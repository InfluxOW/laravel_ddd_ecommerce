<?php

namespace App\Domains\Components\Addressable\Providers;

use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::ADDRESS;

    protected bool $hasMigrations = true;

    protected bool $hasTranslations = true;
}
