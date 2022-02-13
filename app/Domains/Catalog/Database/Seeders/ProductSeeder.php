<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Models\Product;
use App\Infrastructure\Abstracts\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = app()->runningUnitTests() ? 50 : 1000;

        Product::factory()->count($count)->create();
    }
}
