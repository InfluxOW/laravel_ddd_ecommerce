<?php

namespace App\Domains\Catalog\Database\Seeders;

use Akaunting\Money\Currency;
use App\Components\Purchasable\Models\Price;
use App\Domains\Catalog\Console\Commands\UpdateProductsDisplayability;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Infrastructure\Abstracts\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

final class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CatalogSettings $settings)
    {
        $usd = Currency::USD()->getCurrency();
        $rub = Currency::RUB()->getCurrency();

        $settings->available_currencies = app()->runningUnitTests() ? [$usd, $rub] : [$usd, $rub, Currency::EUR()->getCurrency(), Currency::GBP()->getCurrency()];
        $settings->required_currencies = $settings->available_currencies;
        $settings->save();

        $productPricesRows = [];
        foreach (Product::query()->whereDoesntHave('prices')->get(['id']) as $product) {
            foreach ($settings->available_currencies as $currency) {
                $productPricesRows[] = Price::factory()->for($product, 'purchasable')->make(['currency' => $currency])->getRawAttributes(['id']);
            }
        }

        DB::insertByChunks((new Price())->getTable(), LazyCollection::make($productPricesRows), 50, 10);

        Artisan::call(UpdateProductsDisplayability::class);
    }
}
