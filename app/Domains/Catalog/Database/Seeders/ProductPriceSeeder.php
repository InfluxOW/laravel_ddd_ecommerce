<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductPrice;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Illuminate\Database\Seeder;

class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CatalogSettings $settings)
    {
        $settings->available_currencies = app()->runningUnitTests() ? ['USD', 'RUB'] : ['USD', 'RUB', 'EUR', 'GBP'];
        $settings->save();

        foreach (Product::query()->whereDoesntHave('prices')->get() as $product) {
            foreach ($settings->available_currencies as $currency) {
                ProductPrice::factory()->for($product, 'product')->create(['currency' => $currency]);
            }
        }
    }
}
