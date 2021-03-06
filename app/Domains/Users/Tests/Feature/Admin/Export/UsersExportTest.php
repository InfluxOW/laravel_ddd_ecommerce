<?php

namespace App\Domains\Users\Tests\Feature\Admin\Export;

use App\Domains\Admin\Tests\Feature\Admin\ExportTest;
use App\Domains\Users\Admin\Resources\UserResource\Pages\ListUsers;
use App\Domains\Users\Database\Seeders\UserSeeder;

final class UsersExportTest extends ExportTest
{
    protected string $listRecords = ListUsers::class;

    protected function setUpOnce(): void
    {
        $this->seed([
            UserSeeder::class,
        ]);
    }
}
