<?php

namespace App\Domains\Catalog\Tests\Admin\Export;

use App\Domains\Admin\Tests\Admin\ExportTest;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ListProductCategories;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;

final class ProductCategoriesExportTest extends ExportTest
{
    protected string $listRecords = ListProductCategories::class;

    protected function setUpOnce(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
