<?php

namespace App\Domains\Catalog\Jobs\Export;

use App\Domains\Catalog\Enums\Translation\ProductCategoryTranslationKey;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Generic\Classes\Excel\ExportColumn;
use App\Domains\Generic\Jobs\ExportJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class ProductCategoriesExportJob extends ExportJob
{
    public function query(): Builder
    {
        return ProductCategory::query()
            ->with([
                'parent' => fn (BelongsTo $query): BelongsTo => $query->select(['id', 'title']),
                'products' => fn (BelongsToMany $query): BelongsToMany => $query->select(['title']),
            ])
            ->select(['id', 'parent_id', 'title', 'slug', 'description', 'created_at', 'updated_at'])
            ->orderBy('id');
    }

    protected function rows(): Collection
    {
        return collect([
            ExportColumn::create(ProductCategoryTranslationKey::ID, NumberFormat::FORMAT_NUMBER),
            ExportColumn::create(ProductCategoryTranslationKey::TITLE),
            ExportColumn::create(ProductCategoryTranslationKey::PARENT_TITLE),
            ExportColumn::create(ProductCategoryTranslationKey::SLUG),
            ExportColumn::create(ProductCategoryTranslationKey::DESCRIPTION),
            ExportColumn::create(ProductCategoryTranslationKey::PRODUCTS_STRING),
            ExportColumn::create(ProductCategoryTranslationKey::CREATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
            ExportColumn::create(ProductCategoryTranslationKey::UPDATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
        ]);
    }
}
