<?php

namespace App\Domains\Catalog\Tests\Feature\Admin\Export;

use App\Domains\Admin\Tests\AdminExportTest;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ListProductCategories;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;

final class ProductCategoriesAdminExportTest extends AdminExportTest
{
    protected string $listRecords = ListProductCategories::class;

    protected static array $seeders = [
        ProductCategorySeeder::class,
        ProductSeeder::class,
    ];
}
