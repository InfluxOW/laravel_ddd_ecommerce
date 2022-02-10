<?php

namespace App\Application\Classes;

use App\Domains\Generic\Utils\PathUtils;
use Illuminate\Foundation\Application as BaseApplication;

final class Application extends BaseApplication
{
    protected function bindPathsInContainer(): void
    {
        parent::bindPathsInContainer();

        $this->useLangPath(PathUtils::join([__DIR__, '..', '..', 'Domains', 'Generic', 'Resources', 'Lang']));
    }
}
