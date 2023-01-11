<?php

namespace App\Domains\Users\Database\Seeders;

use App\Domains\Common\Database\Seeder;
use App\Domains\Users\Models\User;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedModelByChunks(User::class, app()->runningUnitTests() ? 10 : 100);
    }
}
