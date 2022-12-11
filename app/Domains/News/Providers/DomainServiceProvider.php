<?php

namespace App\Domains\News\Providers;

use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Domains\News\Models\Article;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::NEWS;

    protected array $providers = [
        RouteServiceProvider::class,
    ];

    protected array $morphMap = [
        'article' => Article::class,
    ];
}
