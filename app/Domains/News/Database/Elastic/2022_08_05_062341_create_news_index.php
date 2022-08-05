<?php

use Elastic\Adapter\Indices\Mapping;
use Elastic\Migrations\Facades\Index;
use Elastic\Migrations\MigrationInterface;

return new class implements MigrationInterface {
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::createIfNotExists('news', static function (Mapping $mapping): void {
            $mapping->text('title');
            $mapping->keyword('slug');
            $mapping->text('description');
            $mapping->text('body');
            $mapping->date('published_at');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('news');
    }
};
