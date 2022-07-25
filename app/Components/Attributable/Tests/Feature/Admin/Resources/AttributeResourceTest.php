<?php

namespace App\Components\Attributable\Tests\Feature\Admin\Resources;

use App\Application\Tests\Feature\Admin\AdminCrudTestCase;
use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\CreateAttribute;
use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\EditAttribute;
use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\ListAttributes;
use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\ViewAttribute;
use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Components\Attributable\Models\Attribute;
use Illuminate\Database\Eloquent\Model;

final class AttributeResourceTest extends AdminCrudTestCase
{
    protected ?string $listRecords = ListAttributes::class;

    protected ?string $createRecord = CreateAttribute::class;

    protected ?string $viewRecord = ViewAttribute::class;

    protected ?string $editRecord = EditAttribute::class;

    protected array $seeders = [
        AttributeSeeder::class,
    ];

    protected function getRecord(): ?Model
    {
        return Attribute::query()->first();
    }
}
