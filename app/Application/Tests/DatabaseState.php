<?php

namespace App\Application\Tests;

class DatabaseState
{
    /**
     * Indicates if the test database should be migrated before test suite.
     *
     * @var bool
     */
    public static bool $shouldRunSetUpOnce = true;
}
