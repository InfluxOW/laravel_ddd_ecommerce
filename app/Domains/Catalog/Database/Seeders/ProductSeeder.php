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
        $count = 500;

        if (app()->isLocal()) {
            $count = 1000;
        }

        if (app()->runningUnitTests()) {
            $count = 50;
        }

        Product::factory()->count($count)->create();
    }
}
