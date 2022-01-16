<?php

namespace App\Application\Tests\Admin;

use App\Application\Tests\TestCase;
use App\Domains\Admin\Database\Seeders\AdminSeeder;
use App\Domains\Admin\Models\Admin;
use Filament\Resources\Pages\Page;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;

abstract class AdminTestCase extends TestCase
{
    protected Admin $admin;

    protected array $seeders = [];

    protected function setUpOnce(): void
    {
        parent::setUpOnce();

        $this->seed(array_merge([
            AdminSeeder::class,
        ], $this->seeders));
    }

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Admin $admin */
        $admin = Admin::query()->first();

        $this->admin = $admin;
    }

    /**
     * @param class-string<Page> $page
     * @param array $parameters
     * @return TestableLivewire
     */
    protected function getResourceActionUrl(string $page, array $parameters = []): TestableLivewire
    {
        return Livewire::actingAs($this->admin, 'admin')->test($page, $parameters);
    }
}
