<?php

namespace App\Domains\Catalog\Console\Commands;

use App\Components\Purchasable\Models\Price;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
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
            /** @phpstan-ignore-next-line */
            ->whereHas('categories', fn (Builder|ProductCategory $query) => $query->displayable())
            ->when(true, function (Builder $query): void {
                foreach ($this->settings->required_currencies as $currency) {
                    /** @phpstan-ignore-next-line */
                    $query->whereHas('prices', fn (Builder|Price $query): Builder => $query->where('currency', $currency));
                }
            })
            ->orderBy("{$table}.id");

        DB::transaction(function () use ($table, $query, $now): void {
            $ids = DB::updateByChunks($table, $query, ['is_displayable' => true, 'updated_at' => $now->toDateTime()]);

            DB::updateByChunks($table, DB::table($table)->whereIntegerNotInRaw("{$table}.id", $ids), ['is_displayable' => false, 'updated_at' => $now->toDateTime()]);

            $this->settings->products_displayability_last_updated_at = $now;
            $this->settings->save();
        });

        return self::SUCCESS;
    }
}
