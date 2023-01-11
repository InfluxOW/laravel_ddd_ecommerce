<?php

namespace App\Domains\Catalog;

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
        Index::createIfNotExists('products', static function (Mapping $mapping): void {
            $mapping->text('title');
            $mapping->keyword('slug');
            $mapping->text('description');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('products');
    }
};
