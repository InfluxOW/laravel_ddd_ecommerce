<?php

namespace App\Domain\Catalog\Database\Seeders;

use App\Domain\Catalog\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
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
