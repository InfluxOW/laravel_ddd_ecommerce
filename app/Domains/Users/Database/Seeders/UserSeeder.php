<?php

namespace App\Domains\Users\Database\Seeders;

use App\Domains\Users\Models\User;
use App\Infrastructure\Abstracts\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedModelByChunks(User::class, app()->runningUnitTests() ? 10 : 100);
    }
}
