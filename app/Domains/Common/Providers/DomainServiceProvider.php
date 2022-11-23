<?php

namespace App\Domains\Common\Providers;

use App\Domains\Common\Console\Commands\RefreshApplicationCommand;
use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Domains\Common\Mixins\CacheMixin;
use App\Domains\Common\Mixins\CarbonMixin;
use App\Domains\Common\Mixins\DatabaseMixin;
use App\Domains\Common\Mixins\RequestMixin;
use App\Domains\Common\Mixins\SessionGuardMixin;
use App\Domains\Common\Services\Elastic\AnonymousMigrationFactory;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use Carbon\Carbon;
use Elastic\Migrations\Factories\MigrationFactory;
use Illuminate\Auth\SessionGuard;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\DatabaseManager as DB;
use Illuminate\Http\Request;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::COMMON;

    protected array $commands = [
        RefreshApplicationCommand::class,
    ];

    protected array $providers = [
        RouteServiceProvider::class,
    ];

    protected function afterRegistration(): void
    {
        $this->app->bind(MigrationFactory::class, AnonymousMigrationFactory::class);
    }

    protected function afterBooting(): void
    {
        $this->registerMacroses();
    }

    private function registerMacroses(): void
    {
        DB::mixin(new DatabaseMixin());
        Cache::mixin(new CacheMixin());
        Carbon::mixin(new CarbonMixin());
        Request::mixin(new RequestMixin());
        SessionGuard::mixin(new SessionGuardMixin());
    }
}
