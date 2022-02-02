<?php

namespace App\Infrastructure\Abstracts;

use App\Components\Generic\Enums\ServiceProviderNamespace;
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
     * @var bool Set true if provider will load commands.
     */
    protected bool $hasCommands = false;

    /**
     * @var bool Set true if provider will load migrations.
     */
    protected bool $hasMigrations = false;

    /**
     * @var bool Set true if provider will load translations.
     */
    protected bool $hasTranslations = false;

    /**
     * @var bool Set true if provider will load policies.
     */
    protected bool $hasPolicies = false;

    /**
     * @var bool Set true if provider will load livewire components.
     */
    protected bool $hasLivewireComponents = false;

    /**
     * @var array List of custom Artisan commands.
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

    /**
     * Boot required registering of views and translations.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerTranslations();
        $this->registerLivewireComponents();
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        if ($this->hasPolicies) {
            foreach ($this->policies as $key => $value) {
                Gate::policy($key, $value);
            }
        }
    }

    /**
     * Register domain custom Artisan commands.
     */
    protected function registerCommands(): void
    {
        if ($this->hasCommands) {
            $this->commands($this->commands);
        }
    }

    /**
     * Register domain migrations.
     */
    protected function registerMigrations(): void
    {
        if ($this->hasMigrations) {
            $this->loadMigrationsFrom($this->domainPath('Database/Migrations'));
        }
    }

    /**
     * Register domain translations.
     */
    protected function registerTranslations(): void
    {
        if ($this->hasTranslations) {
            $this->loadTranslationsFrom($this->domainPath('Resources/Lang'), static::NAMESPACE->value);
        }
    }

    /**
     * Register Livewire components.
     */
    protected function registerLivewireComponents(): void
    {
        if ($this->hasLivewireComponents) {
            foreach ($this->livewireComponents as $component) {
                $alias = sprintf('%s.%s', static::NAMESPACE->value, Str::of(Str::of($component)->explode('\\')->last())->kebab());

                Livewire::component($alias, $component);
            }
        }
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

        return ($append === null) ? $realPath : "{$realPath}{$append}";
    }

    /**
     * Register Domain ServiceProviders.
     */
    public function register(): void
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}
