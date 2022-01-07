<?php

namespace App\Domains\Admin\Database\Seeders;

use App\Domains\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::factory()->create(['email' => 'admin@admin.com']);
    }
}
