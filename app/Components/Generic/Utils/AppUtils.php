<?php

namespace App\Components\Generic\Utils;

use App\Components\Generic\Enums\EnvironmentVariable;
use App\Infrastructure\Abstracts\ServiceProviderBase;
use Illuminate\Support\Str;

final class AppUtils
{
    private const PROVIDERS_DIRECTORY_BASENAME = 'Providers';
    private const APP_DIRECTORY_BASENAME = 'app';

    private const APP_NAMESPACE = 'App';

    public static function runningSeeders(): bool
    {
        $runningSeeders = getenv(EnvironmentVariable::RUNNING_SEEDERS->name);

        return is_string($runningSeeders) && $runningSeeders;
    }

    /**
     * @param string $class
     * @return class-string<ServiceProviderBase>|null
     */
    public static function guessDomainServiceProvider(string $class): ?string
    {
        $classParts = Str::of($class)->explode('\\')->reverse();

        foreach ($classParts->keys() as $i) {
            $subclassParts = $classParts
                ->filter(fn (string $part, int $j): bool => $j < $i && $j > 0)
                ->reverse();
            $subclassDirectory = PathUtils::join([PathUtils::getRootDirectoryPath(), self::APP_DIRECTORY_BASENAME, ...$subclassParts]);
            $subclassDirectoryContent = FileUtils::getDirectoryContent($subclassDirectory);
            $possibleSubclassProvidersDirectory = collect($subclassDirectoryContent)
                ->map(fn (string $basename): string => PathUtils::join([$subclassDirectory, $basename]))
                ->filter(fn (string $path): bool => (basename($path) === self::PROVIDERS_DIRECTORY_BASENAME) && is_dir($path))
                ->first();

            if (isset($possibleSubclassProvidersDirectory)) {
                $providersDirectoryContent = FileUtils::getDirectoryContent($possibleSubclassProvidersDirectory);

                /** @var class-string<ServiceProviderBase>|null $domainServiceProvider */
                $domainServiceProvider = collect($providersDirectoryContent)
                    ->map(fn (string $path): string => collect([self::APP_NAMESPACE])->push($subclassParts->implode('\\'), self::PROVIDERS_DIRECTORY_BASENAME, pathinfo($path, PATHINFO_FILENAME))->implode('\\'))
                    ->filter(fn (string $class): bool => is_subclass_of($class, ServiceProviderBase::class))
                    ->first();

                return $domainServiceProvider;
            }
        }

        return null;
    }
}
