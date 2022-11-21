<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Components\Purchasable\Database\Seeders\PriceSeeder;
use App\Domains\Catalog\Console\Commands\UpdateProductsDisplayability;
use App\Domains\Catalog\Models\Product;
use App\Infrastructure\Abstracts\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

final class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(PriceSeeder::class, false, ['purchasableModels' => [Product::class], 'afterInsertHook' => fn () => Artisan::call(UpdateProductsDisplayability::class)]);
    }
}
