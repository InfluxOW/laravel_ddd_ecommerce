<?php

namespace App\Components\Mediable\Providers;

use App\Components\Mediable\Services\Media\FileAdder;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\FileAdder as BaseFileAdder;

final class ComponentServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::MEDIA;

    public function register(): void
    {
        parent::register();

        $this->app->bind(BaseFileAdder::class, FileAdder::class);
    }
}
