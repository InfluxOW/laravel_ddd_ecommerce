<?php

namespace App\Application\Tests;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Generic\Enums\Response\ResponseKey;
use App\Domains\Generic\Http\Middleware\Recaptcha;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\ExpectationFailedException;
use ReflectionClass;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

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

        Http::preventStrayRequests();

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
     * Define actions that should be executed once before the whole suit.
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

    protected function runTest(): mixed
    {
        try {
            return parent::runTest();
        } catch (ExpectationFailedException $e) {
            $this->skipTestIfUnstable();

            throw $e;
        }
    }

    private function skipTestIfUnstable(): void
    {
        $reflection = new ReflectionClass($this);
        $method = $reflection->getMethod($this->getName());
        $docblock = $method->getDocComment();
        $testIsUnstable = is_string($docblock) && str_contains($docblock, '@unstable');

        if ($testIsUnstable) {
            $this->markTestSkipped('Unstable test failed!');
        }
    }

    /*
     * Helpers
     * */

    protected function setIp(string $ip): static
    {
        $this->serverVariables['REMOTE_ADDR'] = $ip;

        return $this;
    }

    protected function withoutRecaptcha(): void
    {
        $this->withoutMiddleware([Recaptcha::class]);
    }

    /**
     * @param class-string<Model> $class
     *
     * @return void
     */
    protected function refreshModelIndex(string $class): void
    {
        try {
            $this->artisan('scout:flush', ['model' => $class]);
        } catch (Missing404Exception) {
        }

        $this->artisan('scout:import', ['model' => $class]);

        $this->waitBecauseOtherwiseItFailsForSomeReason(); // TODO: Fix
    }

    private function waitBecauseOtherwiseItFailsForSomeReason(): void
    {
        sleep(2);
    }

    protected function getResponseData(TestResponse $response): Collection
    {
        return collect($response->json(ResponseKey::DATA->value));
    }

    protected function getResponseAppliedFilters(TestResponse $response): Collection
    {
        return collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));
    }

    protected function getResponseAllowedFilters(TestResponse $response): Collection
    {
        return collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'allowed')));
    }

    protected function getResponseAppliedSort(TestResponse $response): Collection
    {
        return collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::SORT->value, 'applied')));
    }
}
