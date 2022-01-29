<?php

namespace App\Domains\Users\Database\Seeders;

use App\Domains\Users\Models\User;
use App\Infrastructure\Abstracts\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(100)->create();
    }
}
