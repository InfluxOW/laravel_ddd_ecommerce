<?php

namespace App\Domains\Users\Tests\Feature\Admin\Resources;

use App\Application\Tests\Feature\Admin\AdminCrudTestCase;
use App\Domains\Users\Admin\Resources\UserResource;
use App\Domains\Users\Database\Seeders\UserSeeder;

final class UserResourceTest extends AdminCrudTestCase
{
    protected static string $resource = UserResource::class;

    protected array $seeders = [
        UserSeeder::class,
    ];
}
