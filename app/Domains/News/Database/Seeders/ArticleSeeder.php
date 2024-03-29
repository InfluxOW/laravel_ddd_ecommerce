<?php

namespace App\Domains\News\Database\Seeders;

use App\Domains\Common\Database\Seeder;
use App\Domains\News\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 50;

        if (app()->isLocal()) {
            $count = 100;
        }

        if (app()->runningUnitTests()) {
            $count = 10;
        }

        $this->seedModelByChunks(Article::class, $count);
    }
}
