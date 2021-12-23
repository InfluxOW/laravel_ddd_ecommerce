<?php

namespace App\Domain\Products\Database\Seeders;

use App\Domain\Products\Models\Product;
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
