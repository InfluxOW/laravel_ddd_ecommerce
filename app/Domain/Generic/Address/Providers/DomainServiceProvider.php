<?php

namespace App\Domain\Generic\Address\Providers;

use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::ADDRESS;

    protected bool $hasMigrations = true;

    protected bool $hasTranslations = true;
}
