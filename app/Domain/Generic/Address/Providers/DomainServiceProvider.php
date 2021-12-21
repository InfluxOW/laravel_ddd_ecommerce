<?php

namespace App\Domain\Generic\Address\Providers;

use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const ALIAS = 'address';

    protected bool $hasMigrations = true;
}
