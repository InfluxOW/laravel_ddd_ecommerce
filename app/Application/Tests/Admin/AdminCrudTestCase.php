<?php

namespace App\Application\Tests\Admin;

use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

abstract class AdminCrudTestCase extends AdminTestCase
{
    /** @var class-string<ListRecords>|null */
    protected ?string $listRecords = null;

    /** @var class-string<CreateRecord>|null */
    protected ?string $createRecord = null;

    /** @var class-string<ViewRecord>|null */
    protected ?string $viewRecord = null;

    /** @var class-string<EditRecord>|null */
    protected ?string $editRecord = null;

    /** @test */
    public function it_has_crud(): void
    {
        $record = $this->getRecord();

        $this->testRecordsList();
        $this->testRecordCreation();
        $this->testRecordView($record);
        $this->testRecordEditing($record);
    }

    abstract protected function getRecord(): ?Model;

    private function testRecordsList(): void
    {
        if (isset($this->listRecords)) {
            $this->getResourceActionUrl($this->listRecords)->assertOk();
        }
    }

    private function testRecordCreation(): void
    {
        if (isset($this->createRecord)) {
            $this->getResourceActionUrl($this->createRecord)->assertOk();
        }
    }

    private function testRecordView(?Model $record): void
    {
        if (isset($this->viewRecord)) {
            $this->assertNotNull($record);
            /** @phpstan-ignore-next-line  */
            $this->getResourceActionUrl($this->viewRecord, ['record' => $record?->getKey()])->assertOk();

            /** @phpstan-ignore-next-line  */
            foreach ($this->viewRecord::getResource()::getRelations() as $relation) {
                /** @phpstan-ignore-next-line  */
                $this->getResourceActionUrl($this->viewRecord, ['record' => $record?->getKey(), 'activeRelationManager' => $relation])->assertOk();
            }
        }
    }

    private function testRecordEditing(?Model $record): void
    {
        if (isset($this->editRecord)) {
            $this->assertNotNull($record);
            /** @phpstan-ignore-next-line  */
            $this->getResourceActionUrl($this->editRecord, ['record' => $record?->getKey()])->assertOk();

            /** @phpstan-ignore-next-line  */
            foreach ($this->editRecord::getResource()::getRelations() as $relation) {
                /** @phpstan-ignore-next-line  */
                $this->getResourceActionUrl($this->editRecord, ['record' => $record?->getKey(), 'activeRelationManager' => $relation])->assertOk();
            }
        }
    }
}
