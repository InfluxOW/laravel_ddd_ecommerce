<?php

namespace App\Domains\Common\Database\Seeders;

use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Domains\Admin\Database\Seeders\AdminSeeder;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Common\Database\Seeder;
use App\Domains\Feedback\Database\Seeders\FeedbackSeeder;
use App\Domains\News\Database\Seeders\ArticleSeeder;
use App\Domains\Users\Database\Seeders\UserSeeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FeedbackSeeder::class,
            AdminSeeder::class,
            ProductCategorySeeder::class,
            AttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
            ProductPriceSeeder::class,
            ArticleSeeder::class,
        ]);

        ProductCategory::loadHierarchy();
    }
}
