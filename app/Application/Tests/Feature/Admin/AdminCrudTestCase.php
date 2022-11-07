<?php

namespace App\Application\Tests\Feature\Admin;

use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Database\Eloquent\Model;

abstract class AdminCrudTestCase extends AdminTestCase
{
    /** @var class-string<FilamentResource> */
    protected static string $resource;

    /** @var class-string<ListRecords>|null */
    private ?string $listRecords = null;

    /** @var class-string<CreateRecord>|null */
    private ?string $createRecord = null;

    /** @var class-string<ViewRecord>|null */
    private ?string $viewRecord = null;

    /** @var class-string<EditRecord>|null */
    private ?string $editRecord = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->preparePages();
    }

    /** @test */
    public function it_has_crud(): void
    {
        $record = static::getRecord();

        $this->testListRecords();
        $this->testCreateRecord();
        $this->testViewRecord($record);
        $this->testEditRecord($record);
    }

    protected static function getRecord(): ?Model
    {
        return static::$resource::getEloquentQuery()->withoutEagerLoads()->first();
    }

    private function testListRecords(): void
    {
        if (isset($this->listRecords)) {
            $this->getResourceActionUrl($this->listRecords)->assertOk();
        }
    }

    private function testCreateRecord(): void
    {
        if (isset($this->createRecord)) {
            $this->getResourceActionUrl($this->createRecord)->assertOk();
        }
    }

    private function testViewRecord(?Model $record): void
    {
        if (isset($this->viewRecord)) {
            $this->assertNotNull($record);
            $this->getResourceActionUrl($this->viewRecord, ['record' => $record->getKey()])->assertOk();

            foreach (static::$resource::getRelations() as $relation) {
                $this->getResourceActionUrl($this->viewRecord, ['record' => $record->getKey(), 'activeRelationManager' => $relation])->assertOk();
            }
        }
    }

    private function testEditRecord(?Model $record): void
    {
        if (isset($this->editRecord)) {
            $this->assertNotNull($record);
            $this->getResourceActionUrl($this->editRecord, ['record' => $record->getKey()])->assertOk();

            foreach (static::$resource::getRelations() as $relation) {
                $this->getResourceActionUrl($this->editRecord, ['record' => $record->getKey(), 'activeRelationManager' => $relation])->assertOk();
            }
        }
    }

    private function preparePages(): void
    {
        foreach (static::$resource::getPages() as ['class' => $page]) {
            /** @var array<string, class-string> $parents */
            $parents = class_parents($page);

            foreach ([ListRecords::class => &$this->listRecords, CreateRecord::class => &$this->createRecord, EditRecord::class => &$this->editRecord, ViewRecord::class => &$this->viewRecord] as $type => &$attribute) {
                if (isset($parents[$type])) {
                    $attribute = $page;

                    break;
                }
            }
        }
    }
}
