<?php

namespace App\Domains\Feedback;

use Elastic\Adapter\Indices\Mapping;
use Elastic\Migrations\Facades\Index;
use Elastic\Migrations\MigrationInterface;

return new class () implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::createIfNotExists('feedback', static function (Mapping $mapping): void {
            $mapping->text('text');
            $mapping->text('username');
            $mapping->text('email');
            $mapping->text('phone');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('feedback');
    }
};
