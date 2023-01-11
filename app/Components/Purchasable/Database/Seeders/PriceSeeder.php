<?php

namespace App\Components\Purchasable\Database\Seeders;

use Akaunting\Money\Currency;
use App\Components\Purchasable\Models\Price;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Domains\Common\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

final class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param class-string<Model>[] $purchasableModels
     */
    public function run(CatalogSettings $settings, array $purchasableModels = [], ?callable $afterInsertHook = null): void
    {
        $usd = Currency::USD()->getCurrency();
        $rub = Currency::RUB()->getCurrency();

        $settings->available_currencies = app()->runningUnitTests() ? [$usd, $rub] : [$usd, $rub, Currency::EUR()->getCurrency(), Currency::GBP()->getCurrency()];
        $settings->required_currencies = $settings->available_currencies;
        $settings->save();

        $pricesRows = [];
        foreach ($purchasableModels as $purchasableModel) {
            foreach ($purchasableModel::query()->whereDoesntHave('prices')->get(['id']) as $purchasable) {
                foreach ($settings->available_currencies as $currency) {
                    $pricesRows[] = Price::factory()->for($purchasable, 'purchasable')->make(['currency' => $currency])->getRawAttributes(['id']);
                }
            }
        }

        DB::insertByChunks((new Price())->getTable(), LazyCollection::make($pricesRows), 50, 10);

        if (isset($afterInsertHook)) {
            $afterInsertHook();
        }
    }
}
