<?php

namespace App\Domain\Admin\Providers;

use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const ALIAS = 'admin';

    protected bool $hasMigrations = true;

    protected array $providers = [
    ];
}
