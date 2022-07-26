<?php

namespace App\Domains\Generic\Providers;

use App\Domains\Generic\Console\Commands\RefreshApplicationCommand;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Generic\Services\Database;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as RequestFacade;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::GENERIC;

    protected array $commands = [
        RefreshApplicationCommand::class,
    ];

    protected array $providers = [
        RouteServiceProvider::class,
    ];

    protected function afterBooting(): void
    {
        $this->registerMacroses();
    }

    private function registerMacroses(): void
    {
        /* @phpstan-ignore-next-line */
        SessionGuard::macro('retrieveUserByCredentials', fn (array $credentials): ?Authenticatable => $this->provider->retrieveByCredentials($credentials));

        RequestFacade::macro('getIp', function (): ?string {
            /** @var Request $request */
            $request = $this;

            $remotesKeys = [
                'HTTP_X_FORWARDED_FOR',
                'HTTP_CLIENT_IP',
                'HTTP_X_REAL_IP',
                'HTTP_X_FORWARDED',
                'X-FORWARDED-FOR',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR',
                'HTTP_X_CLUSTER_CLIENT_IP',
            ];

            foreach ($remotesKeys as $key) {
                $address = $request->header($key) ?? getenv($key);

                if (is_string($address)) {
                    foreach (explode(',', $address) as $ip) {
                        $ip = trim($ip);

                        if (
                            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false &&
                            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE) === false
                        ) {
                            continue;
                        }

                        return $ip;
                    }
                }
            }

            return $request->ip();
        });

        DB::macro('insertByChunks', fn (string $table, Enumerable $rows, int $chunkSize = 100, int $chunkSliceSize = 20): array => app(Database::class)->insertByChunks($table, $rows, $chunkSize, $chunkSliceSize));
        DB::macro('updateByChunks', fn (string $table, Builder|EloquentBuilder $query, array $updates, int $chunkSize = 100, int $chunkSliceSize = 20): array => app(Database::class)->updateByChunks($table, $query, $updates, $chunkSize, $chunkSliceSize));
    }
}
