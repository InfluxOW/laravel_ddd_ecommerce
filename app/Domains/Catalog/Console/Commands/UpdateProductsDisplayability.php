<?php

namespace App\Domains\Catalog\Console\Commands;

use App\Components\Purchasable\Database\Builders\PriceBuilder;
use App\Domains\Catalog\Database\Builders\ProductBuilder;
use App\Domains\Catalog\Database\Builders\ProductCategoryBuilder;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class UpdateProductsDisplayability extends Command
{
    protected $signature = 'app:catalog:update_products_displayability';

    protected $description = 'Recalculate products displayability';

    public function __construct(private CatalogSettings $settings)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $table = (new Product())->getTable();
        $now = Carbon::now();
        $query = Product::query()
            ->where("{$table}.is_visible", true)
            ->whereHas('categories', fn (ProductCategoryBuilder $query) => $query->displayable())
            ->when(true, function (ProductBuilder $query): void {
                foreach ($this->settings->required_currencies as $currency) {
                    $query->whereHas('prices', fn (PriceBuilder $query) => $query->where('currency', $currency));
                }
            })
            ->orderBy("{$table}.id");

        DB::transaction(function () use ($table, $query, $now): void {
            $ids = DB::updateByChunks($table, $query, ['is_displayable' => true, 'updated_at' => $now->toDateTime()]);

            DB::updateByChunks($table, DB::table($table)->whereIntegerNotInRaw("{$table}.id", $ids), ['is_displayable' => false, 'updated_at' => $now->toDateTime()]);

            $this->settings->refresh();
            $this->settings->products_displayability_last_updated_at = $now;
            $this->settings->save();
        });

        return self::SUCCESS;
    }
}
