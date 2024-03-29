<?php

namespace App\Infrastructure\Abstracts\Providers;

use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Domains\Common\Utils\PathUtils;
use Closure;
use Elastic\Migrations\Filesystem\MigrationStorage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;
use ReflectionClass;

abstract class ServiceProvider extends LaravelServiceProvider
{
    /*
     * Namespace for loading translations.
     * */
    public const NAMESPACE = ServiceProviderNamespace::DEFAULT;

    /**
     * @var array List of Artisan commands to load.
     */
    protected array $commands = [];

    /**
     * @var array List of providers to load.
     */
    protected array $providers = [];

    /**
     * @var array List of policies to load.
     */
    protected array $policies = [];

    /**
     * @var array List of Livewire components to load.
     */
    protected array $livewireComponents = [];

    protected array $morphMap = [];

    /**
     * Register Domain ServiceProviders.
     */
    public function register(): void
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }

        $this->afterRegistration();
    }

    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerTranslations();
        $this->registerLivewireComponents();
        $this->registerViews();
        $this->registerExceptionHandlingCallbacks();
        $this->app->resolving(Schedule::class, fn (Schedule $schedule) => $this->registerSchedule($schedule));

        $this->loadMorphMap();

        $this->afterBooting();
    }

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    protected function registerCommands(): void
    {
        $this->commands($this->commands);
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom($this->domainPath(PathUtils::join(['Database', 'Migrations'])));
        app(MigrationStorage::class)->registerPaths([$this->domainPath(PathUtils::join(['Database', 'Elastic']))]);
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom($this->domainPath(PathUtils::join(['Resources', 'Lang'])), static::NAMESPACE->value);
    }

    protected function registerLivewireComponents(): void
    {
        foreach ($this->livewireComponents as $component) {
            /** @var string $basename */
            $basename = Str::of($component)->explode('\\')->last();
            $alias = sprintf('%s.%s', static::NAMESPACE->value, Str::of($basename)->kebab());

            Livewire::component($alias, $component);
        }
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom($this->domainPath(PathUtils::join(['Resources', 'Views'])), static::NAMESPACE->value);
    }

    private function registerExceptionHandlingCallbacks(): void
    {
        $handler = $this->app->make(ExceptionHandler::class);

        /** @phpstan-ignore-next-line */
        if ($handler instanceof Handler) {
            foreach ($this->getCustomExceptionRenderers() as $renderer) {
                $handler->renderable($renderer);
            }

            foreach ($this->getCustomExceptionReporters() as $reporter) {
                $handler->reportable($reporter);
            }
        }
    }

    /**
     * @return Closure[]
     */
    protected function getCustomExceptionRenderers(): array
    {
        return [];
    }

    /**
     * @return Closure[]
     */
    protected function getCustomExceptionReporters(): array
    {
        return [];
    }

    protected function registerSchedule(Schedule $schedule): void
    {
        //
    }

    private function loadMorphMap(): void
    {
        Relation::morphMap($this->morphMap);
    }

    protected function afterRegistration(): void
    {
        //
    }

    protected function afterBooting(): void
    {
        //
    }

    /**
     * Detects the domain base path so resources can be proper loaded on child classes.
     */
    protected function domainPath(?string $append = null): string
    {
        $reflection = new ReflectionClass($this);
        /** @var string $path */
        $path = $reflection->getFileName();
        $realPath = dirname($path, 2) . '/';

        return $append === null ? $realPath : "{$realPath}{$append}";
    }
}
