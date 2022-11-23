<?php

namespace App\Application\Tests;

use App\Domains\Common\Utils\PathUtils;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require PathUtils::join([__DIR__, '..', '..', '..', 'bootstrap', 'app.php']);

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
