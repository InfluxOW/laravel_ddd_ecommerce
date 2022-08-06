<?php

namespace App\Components\Attributable\Tests\Feature\Admin\Resources;

use App\Application\Tests\Feature\Admin\AdminCrudTestCase;
use App\Components\Attributable\Admin\Resources\AttributeResource;
use App\Components\Attributable\Database\Seeders\AttributeSeeder;

final class AttributeResourceTest extends AdminCrudTestCase
{
    protected static string $resource = AttributeResource::class;

    protected static array $seeders = [
        AttributeSeeder::class,
    ];
}
