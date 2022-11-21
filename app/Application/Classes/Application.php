<?php

namespace App\Application\Classes;

use App\Domains\Generic\Utils\PathUtils;
use Illuminate\Foundation\Application as BaseApplication;

final class Application extends BaseApplication
{
    protected function bindPathsInContainer(): void
    {
        $this->databasePath = PathUtils::join([__DIR__, '..', '..', 'Domains', 'Generic', 'Database']);

        parent::bindPathsInContainer();

        $this->useLangPath($this->resourcePath('Lang'));
    }

    public function databasePath($path = ''): string
    {
        return str_replace('migrations', 'Migrations', parent::databasePath($path));
    }

    public function resourcePath($path = ''): string
    {
        $resourcePath = PathUtils::join([__DIR__, '..', '..', 'Domains', 'Generic', 'Resources']);

        return $path === '' ? $resourcePath : PathUtils::join([$resourcePath, $path]);
    }

    public function isRunningSeeders(): bool
    {
        return ApplicationState::$isRunningSeeders;
    }
}
