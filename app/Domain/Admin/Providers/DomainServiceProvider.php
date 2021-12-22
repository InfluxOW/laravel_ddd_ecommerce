<?php

namespace App\Domain\Admin\Providers;

use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::ADMIN;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
}
