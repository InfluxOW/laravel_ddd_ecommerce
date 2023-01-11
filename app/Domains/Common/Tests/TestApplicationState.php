<?php

namespace App\Domains\Common\Tests;

final class TestApplicationState
{
    /**
     * Indicates if the suit's `setUpOnce` method should be executed.
     */
    public static bool $shouldSetUpOnce = true;
}
