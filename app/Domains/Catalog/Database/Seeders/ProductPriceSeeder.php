<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Components\Purchasable\Database\Seeders\PriceSeeder;
use App\Domains\Catalog\Console\Commands\UpdateProductsDisplayability;
use App\Domains\Catalog\Models\Product;
use App\Infrastructure\Abstracts\Database\Seeder;

final class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PriceSeeder::class, false, ['purchasableModels' => [Product::class], fn () => $this->call(UpdateProductsDisplayability::class)]);
    }
}
