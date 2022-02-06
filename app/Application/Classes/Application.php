<?php

namespace App\Application\Classes;

use App\Domains\Generic\Utils\PathUtils;
use Illuminate\Foundation\Application as BaseApplication;

final class Application extends BaseApplication
{
    public function langPath(): string
    {
        return PathUtils::join([__DIR__, '..', '..', 'Domains', 'Generic', 'Resources', 'Lang']);
    }
}
