<?php

namespace App\Domains\Generic\Utils;

use App\Domains\Generic\Enums\EnvironmentVariable;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use Illuminate\Support\Str;

final class AppUtils
{
    private const PROVIDERS_DIRECTORY_BASENAME = 'Providers';
    private const APP_DIRECTORY_BASENAME = 'app';

    private const APP_NAMESPACE = 'App';

    public static function runningSeeders(): bool
    {
        $runningSeeders = env(EnvironmentVariable::RUNNING_SEEDERS->name);

        return is_string($runningSeeders) && $runningSeeders;
    }

    public static function guessServiceProviderNamespace(string $class): ServiceProviderNamespace
    {
        /** @var ServiceProvider|null $domainServiceProvider */
        $domainServiceProvider = self::guessDomainServiceProvider($class);

        return ($domainServiceProvider === null) ? ServiceProviderNamespace::DEFAULT : $domainServiceProvider::NAMESPACE;
    }

    /**
     * @param string $class
     * @return class-string<ServiceProvider>|null
     */
    private static function guessDomainServiceProvider(string $class): ?string
    {
        $classParts = Str::of($class)->explode('\\')->reverse();

        foreach ($classParts->keys() as $i) {
            $subclassParts = $classParts
                ->filter(fn (string $part, int $j): bool => $j < $i && $j > 0)
                ->reverse();
            $subclassDirectory = PathUtils::join([base_path(), self::APP_DIRECTORY_BASENAME, ...$subclassParts]);
            $subclassDirectoryContent = FileUtils::getDirectoryContent($subclassDirectory);
            $possibleSubclassProvidersDirectory = collect($subclassDirectoryContent)
                ->map(fn (string $basename): string => PathUtils::join([$subclassDirectory, $basename]))
                ->filter(fn (string $path): bool => (basename($path) === self::PROVIDERS_DIRECTORY_BASENAME) && is_dir($path))
                ->first();

            if (isset($possibleSubclassProvidersDirectory)) {
                $providersDirectoryContent = FileUtils::getDirectoryContent($possibleSubclassProvidersDirectory);

                /** @var class-string<ServiceProvider>|null $domainServiceProvider */
                $domainServiceProvider = collect($providersDirectoryContent)
                    ->map(fn (string $path): string => collect([self::APP_NAMESPACE])->push($subclassParts->implode('\\'), self::PROVIDERS_DIRECTORY_BASENAME, pathinfo($path, PATHINFO_FILENAME))->implode('\\'))
                    ->filter(fn (string $class): bool => is_subclass_of($class, ServiceProvider::class))
                    ->first();

                return $domainServiceProvider;
            }
        }

        return null;
    }
}
