<?php

namespace App\Domains\Admin\Providers;

use App\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::ADMIN;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
}
