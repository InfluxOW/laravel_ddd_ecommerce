<?php

namespace App\Domains\Admin\Database\Seeders;

use App\Domains\Admin\Models\Admin;
use App\Infrastructure\Abstracts\Database\Seeder;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('app.admin.email');
        if (Admin::query()->where('email', $email)->doesntExist()) {
            Admin::factory()->create(['email' => $email, 'password' => bcrypt(config('app.admin.password'))]);
        }
    }
}
