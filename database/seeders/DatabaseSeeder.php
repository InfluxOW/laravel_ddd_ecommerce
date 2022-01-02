<?php

namespace Database\Seeders;

use App\Domain\Admin\Database\Seeders\AdminSeeder;
use App\Domain\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domain\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domain\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domain\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domain\Catalog\Database\Seeders\ProductSeeder;
use App\Domain\Users\Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

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
            AdminSeeder::class,
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
            ProductPriceSeeder::class,
        ]);
    }
}
