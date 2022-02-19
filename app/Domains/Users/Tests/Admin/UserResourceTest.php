<?php

namespace App\Domains\Users\Tests\Admin;

use App\Application\Tests\Admin\AdminCrudTestCase;
use App\Domains\Users\Admin\Resources\UserResource\Pages\EditUser;
use App\Domains\Users\Admin\Resources\UserResource\Pages\ListUsers;
use App\Domains\Users\Admin\Resources\UserResource\Pages\ViewUser;
use App\Domains\Users\Database\Seeders\UserSeeder;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Model;

final class UserResourceTest extends AdminCrudTestCase
{
    protected ?string $listRecords = ListUsers::class;
    protected ?string $viewRecord = ViewUser::class;
    protected ?string $editRecord = EditUser::class;

    protected array $seeders = [
        UserSeeder::class,
    ];

    protected function getRecord(): ?Model
    {
        return User::query()->first();
    }
}
