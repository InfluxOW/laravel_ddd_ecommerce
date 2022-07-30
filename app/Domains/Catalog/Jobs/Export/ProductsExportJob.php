<?php

namespace App\Domains\Catalog\Jobs\Export;

use App\Domains\Catalog\Enums\Translation\ProductTranslationKey;
use App\Domains\Catalog\Models\Product;
use App\Domains\Generic\Classes\Excel\ExportColumn;
use App\Domains\Generic\Jobs\ExportJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class ProductsExportJob extends ExportJob
{
    protected function getBaseQuery(): Builder
    {
        return Product::query()
            ->with([
                'categories' => fn (BelongsToMany $query): BelongsToMany => $query->select(['title']),
                'prices' => fn (MorphMany $query): MorphMany => $query->select(['price', 'price_discounted', 'currency', 'purchasable_id', 'purchasable_type']),
                'attributeValues' => fn (MorphMany $query): MorphMany => $query->with(['attribute' => fn (BelongsTo $query): BelongsTo => $query->select(['id', 'title', 'values_type'])])->select(['attribute_id', 'attributable_id', 'attributable_type', 'value_string', 'value_integer', 'value_float', 'value_boolean']),
            ])
            ->select(['id', 'title', 'slug', 'created_at', 'updated_at']);
    }

    protected function rows(): Collection
    {
        return collect([
            ExportColumn::create(ProductTranslationKey::ID, NumberFormat::FORMAT_NUMBER),
            ExportColumn::create(ProductTranslationKey::TITLE),
            ExportColumn::create(ProductTranslationKey::SLUG),
            ExportColumn::create(ProductTranslationKey::CATEGORIES_STRING),
            ExportColumn::create(ProductTranslationKey::ATTRIBUTE_VALUES_STRING),
            ExportColumn::create(ProductTranslationKey::PRICES_STRING),
            ExportColumn::create(ProductTranslationKey::CREATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
            ExportColumn::create(ProductTranslationKey::UPDATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
        ]);
    }
}
