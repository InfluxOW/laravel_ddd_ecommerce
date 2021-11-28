<?php

namespace Database\Seeders;

use App\Domain\Products\Database\Seeders\ProductAttributeSeeder;
use App\Domain\Products\Database\Seeders\ProductAttributeValueSeeder;
use App\Domain\Products\Database\Seeders\ProductCategorySeeder;
use App\Domain\Products\Database\Seeders\ProductSeeder;
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
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
        ]);
    }
}
