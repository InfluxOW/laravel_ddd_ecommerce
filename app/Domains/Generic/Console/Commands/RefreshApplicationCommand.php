<?php

namespace App\Domains\Generic\Console\Commands;

use App\Domains\Generic\Database\Seeders\DatabaseSeeder;
use Elastic\Transport\Exception\TransportException;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;

final class RefreshApplicationCommand extends Command
{
    protected $signature = 'app:refresh';

    protected $description = 'Re-fill all the Application data';

    public function handle(): int
    {
        if (app()->isProduction()) {
            return self::INVALID;
        }

        $this->restartHorizon();
        $this->clearMedia();
        $this->refreshDatabase();
        $this->clearCache();
        $this->fillDatabase();
        $this->refreshElastic();
        $this->fillElastic();

        return self::SUCCESS;
    }

    private function restartHorizon(): void
    {
        $this->call('horizon:terminate', ['--wait' => true]);
    }

    private function clearMedia(): void
    {
        try {
            $this->call('media-library:clear');
        } catch (QueryException) {
        }
    }

    private function refreshDatabase(): void
    {
        $this->call('migrate:fresh');
    }

    private function clearCache(): void
    {
        Redis::flushall();
    }

    private function fillDatabase(): void
    {
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);
    }

    private function refreshElastic(): void
    {
        try {
            $this->call('elastic:migrate:refresh');
        } catch (TransportException) {
        }
    }

    private function fillElastic(): void
    {
        foreach (config('scout.models') as $model) {
            $this->call('scout:import', ['model' => $model]);
        }
    }
}
