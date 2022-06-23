<?php

namespace App\Domains\Generic\Console\Commands;

use App\Domains\Generic\Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RefreshApplication extends Command
{
    protected $signature = 'app:refresh';

    protected $description = 'Re-fill all the Application data';

    public function handle(): int
    {
        if (app()->isProduction()) {
            return self::INVALID;
        }

        $this->call('media-library:clear');
        $this->call('migrate:fresh');
        Redis::flushall();
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);

        return self::SUCCESS;
    }
}
