<?php

namespace App\Components\Addressable\Providers;

use App\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class ComponentServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::ADDRESS;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
}
