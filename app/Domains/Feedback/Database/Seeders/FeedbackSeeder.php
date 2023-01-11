<?php

namespace App\Domains\Feedback\Database\Seeders;

use App\Domains\Common\Database\Seeder;
use App\Domains\Feedback\Models\Feedback;

final class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedModelByChunks(Feedback::class, app()->runningUnitTests() ? 20 : 200, 25, 5);
    }
}
