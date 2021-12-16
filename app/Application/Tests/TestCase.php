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

    protected static array $seeders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->beginDatabaseTransaction();
    }

    public function refreshDatabase(): void
    {
        if (DatabaseState::$shouldBeMigrated) {
            $this->baseLazilyRefreshDatabase();
        }

        DatabaseState::$shouldBeMigrated = false;
    }

    protected function refreshTestDatabase(): void
    {
        if (RefreshDatabaseState::$migrated) {
            return;
        }

        $this->artisan('migrate:fresh', $this->migrateFreshUsing());

        /* @phpstan-ignore-next-line  */
        $this->app[Kernel::class]->setArtisan(null);

        RefreshDatabaseState::$migrated = true;
    }
}
