<?php

namespace App\Domains\Feedback\Database\Seeders;

use App\Domains\Feedback\Models\Feedback;
use App\Infrastructure\Abstracts\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Feedback::factory()->count(200)->create();
    }
}
