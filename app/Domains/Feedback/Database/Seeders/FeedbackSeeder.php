<?php

namespace App\Domains\Feedback\Database\Seeders;

use App\Domains\Feedback\Models\Feedback;
use App\Infrastructure\Abstracts\Database\Seeder;

final class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedModelByChunks(Feedback::class, app()->runningUnitTests() ? 20 : 200, 25, 5);
    }
}
