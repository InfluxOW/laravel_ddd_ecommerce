<?php

namespace App\Domains\Common\Console\Commands;

use Illuminate\Console\Command;

final class InsightsCommand extends Command
{
    protected $signature = 'insights {dir?} {--summary} {--fix}';

    protected $description = 'Temporarily stub for insights command';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
