<?php

namespace App\Domains\Admin\Database\Seeders;

use App\Domains\Admin\Models\Admin;
use App\Infrastructure\Abstracts\Seeder;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'admin@admin.com';
        if (Admin::query()->where('email', $email)->doesntExist()) {
            Admin::factory()->create(['email' => $email]);
        }
    }
}
