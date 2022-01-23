<?php

namespace App\Application\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Redis;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase {
        refreshDatabase as baseLazilyRefreshDatabase;
    }

    /**
     * Set to `true` if you don't want to recreate existing database.
     * Could be useful if you found an unstable test and want to rerun it
     * with data that caused that failure.
     */
    protected static bool $doNotRecreateDatabase = false;

    protected function setUp(): void
    {
        if (static::$doNotRecreateDatabase) {
            DatabaseState::$shouldRunSetUpOnce = false;
            RefreshDatabaseState::$migrated = true;
        }

        parent::setUp();

        Redis::flushall();

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

    protected function setIp(string $ip): static
    {
        $this->serverVariables['REMOTE_ADDR'] = $ip;

        return $this;
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
        RefreshDatabaseState::$migrated = false;

        parent::tearDownAfterClass();
    }
}
