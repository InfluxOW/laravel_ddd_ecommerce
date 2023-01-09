<?php

namespace App\Domains\Catalog\Console\Commands;

use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class UpdateProductCategoriesDisplayability extends Command
{
    protected $signature = 'app:catalog:update_product_categories_displayability';

    protected $description = 'Recalculate product categories displayability';

    public function __construct(private readonly CatalogSettings $settings)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $table = (new ProductCategory())->getTable();
        $now = Carbon::now();
        $query = ProductCategory::query()
            ->whereIntegerInRaw("{$table}.id", ProductCategory::mapHierarchy(static fn (ProductCategory $category) => $category->id, ProductCategory::getVisibleHierarchy()))
            ->hasLimitedDepth()
            ->orderBy("{$table}.id");

        DB::transaction(function () use ($table, $query, $now): void {
            $ids = DB::updateByChunks($table, $query, ['is_displayable' => true]);

            DB::updateByChunks($table, DB::table($table)->whereIntegerNotInRaw("{$table}.id", $ids), ['is_displayable' => false]);

            $this->settings->refresh();
            $this->settings->product_categories_displayability_last_updated_at = $now;
            $this->settings->save();
        });

        ProductCategory::loadHierarchy();

        return self::SUCCESS;
    }
}
