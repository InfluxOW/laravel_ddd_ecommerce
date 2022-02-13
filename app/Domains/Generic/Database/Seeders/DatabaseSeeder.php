<?php

namespace App\Domains\Generic\Database\Seeders;

use App\Domains\Admin\Database\Seeders\AdminSeeder;
use App\Domains\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Feedback\Database\Seeders\FeedbackSeeder;
use App\Domains\Users\Database\Seeders\UserSeeder;
use App\Infrastructure\Abstracts\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            FeedbackSeeder::class,
            AdminSeeder::class,
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
            ProductPriceSeeder::class,
        ]);

        ProductCategory::loadHierarchy();
    }
}
