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

        if (isset($this->listRecords)) {
            $this->getResourceActionUrl($this->listRecords)->assertOk();
        }

        if (isset($this->createRecord)) {
            $this->getResourceActionUrl($this->createRecord)->assertOk();
        }

        if (isset($this->viewRecord)) {
            $this->assertNotNull($record);
            /** @phpstan-ignore-next-line  */
            $this->getResourceActionUrl($this->viewRecord, ['record' => $record?->id])->assertOk();

            /** @phpstan-ignore-next-line  */
            foreach ($this->viewRecord::getResource()::getRelations() as $relation) {
                /** @phpstan-ignore-next-line  */
                $this->getResourceActionUrl($this->viewRecord, ['record' => $record?->id, 'activeRelationManager' => $relation])->assertOk();
            }
        }

        if (isset($this->editRecord)) {
            $this->assertNotNull($record);
            /** @phpstan-ignore-next-line  */
            $this->getResourceActionUrl($this->editRecord, ['record' => $record?->id])->assertOk();

            /** @phpstan-ignore-next-line  */
            foreach ($this->editRecord::getResource()::getRelations() as $relation) {
                /** @phpstan-ignore-next-line  */
                $this->getResourceActionUrl($this->editRecord, ['record' => $record?->id, 'activeRelationManager' => $relation])->assertOk();
            }
        }
    }

    abstract protected function getRecord(): ?Model;
}
