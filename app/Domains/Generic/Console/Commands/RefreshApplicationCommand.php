<?php

namespace App\Domains\Generic\Console\Commands;

use App\Domains\Generic\Database\Seeders\DatabaseSeeder;
use Elastic\Transport\Exception\RuntimeException;
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

        $this->call('horizon:terminate', ['--wait' => true]);
        try {
            $this->call('media-library:clear');
        } catch (QueryException) {
        }
        $this->call('migrate:fresh');
        Redis::flushall();
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);
        try {
            $this->call('elastic:migrate:refresh');
        } catch (RuntimeException) {
        }
        foreach (config('scout.models') as $model) {
            $this->call('scout:import', ['model' => $model]);
        }

        return self::SUCCESS;
    }
}
