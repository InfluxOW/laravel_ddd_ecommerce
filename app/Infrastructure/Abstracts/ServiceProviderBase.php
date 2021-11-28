<?php

namespace App\Infrastructure\Abstracts;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use ReflectionClass;

abstract class ServiceProviderBase extends LaravelServiceProvider
{
    /*
     * Alias for loading translations and views
     * */
    public const ALIAS = null;

    /**
     * @var bool Set true if provider will load commands
     */
    protected bool $hasCommands = false;

    /**
     * @var bool Set true if provider will load migrations
     */
    protected bool $hasMigrations = false;

    /**
     * @var bool Set true if provider will load translations
     */
    protected bool $hasTranslations = false;

    /**
     * @var bool Set true if provider will load policies
     */
    protected bool $hasPolicies = false;

    /**
     * @var array List of custom Artisan commands
     */
    protected array $commands = [];

    /**
     * @var array List of providers to load
     */
    protected array $providers = [];

    /**
     * @var array List of policies to load
     */
    protected array $policies = [];

    /**
     * Boot required registering of views and translations.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerTranslations();
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
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
     * Register domain translations.
     */
    protected function registerTranslations(): void
    {
        if ($this->hasTranslations) {
            $this->loadTranslationsFrom($this->domainPath('Resources/Lang'), static::ALIAS);
        }
    }

    /**
     * Register Domain ServiceProviders.
     */
    public function register(): void
    {
        collect($this->providers)->each(function ($providerClass) {
            $this->app->register($providerClass);
        });
    }
}
