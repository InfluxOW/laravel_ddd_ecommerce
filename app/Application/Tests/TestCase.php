<?php

namespace App\Application\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase {
        refreshDatabase as baseLazilyRefreshDatabase;
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (DatabaseState::$shouldRunSetUpOnce) {
            $this->setUpOnce();

            DatabaseState::$shouldRunSetUpOnce = false;
        }

        $this->beginDatabaseTransaction();
    }

    protected function refreshTestDatabase(): void
    {
        if (RefreshDatabaseState::$migrated) {
            return;
        }

        $this->artisan('migrate:fresh', $this->migrateFreshUsing());

        /* @phpstan-ignore-next-line */
        $this->app[Kernel::class]->setArtisan(null);

        RefreshDatabaseState::$migrated = true;
    }

    /**
     * Define actions that should be executed once before the whole suit
     */
    protected function setUpOnce(): void
    {
        //
    }

    public static function tearDownAfterClass(): void
    {
        DatabaseState::$shouldRunSetUpOnce = true;

        parent::tearDownAfterClass();
    }
}
