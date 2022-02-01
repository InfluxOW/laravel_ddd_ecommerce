<?php

namespace App\Components\Mediable\Providers;

use App\Components\Mediable\Services\Media\FileAdder;
use App\Infrastructure\Abstracts\BaseServiceProvider;
use Spatie\MediaLibrary\MediaCollections\FileAdder as BaseFileAdder;

class ComponentServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->bind(BaseFileAdder::class, FileAdder::class);
    }
}
