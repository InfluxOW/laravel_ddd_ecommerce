<?php

namespace App\Components\Attributable\Tests\Feature\Admin\Resources;

use App\Components\Attributable\Admin\Resources\AttributeResource;
use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Domains\Admin\Tests\AdminCrudTestCase;

final class AttributeResourceTest extends AdminCrudTestCase
{
    protected static string $resource = AttributeResource::class;

    protected static array $seeders = [
        AttributeSeeder::class,
    ];
}
