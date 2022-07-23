<?php

namespace App\Domains\Admin\Tests\Feature\Admin;

use App\Application\Tests\TestCase;
use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Admin\Admin\Components\Actions\Tables\ExportAction;
use App\Domains\Admin\Enums\Translation\Components\Actions\ExportActionTranslationKey;
use App\Domains\Admin\Enums\Translation\ExportFormat;
use App\Domains\Generic\Jobs\ExportJob;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;

abstract class ExportTest extends TestCase
{
    /** @var class-string<ListRecords> */
    protected string $listRecords;

    /** @test */
    public function export_works_correctly(): void
    {
        /** @var class-string<ExportJob> $exportJob */
        $exportJob = $this->listRecords::getResource()::getModel()::getExportJob();

        foreach (ExportFormat::cases() as $format) {
            Excel::fake();
            Excel::matchByRegex();

            Livewire::test($this->listRecords)->callTableAction(ExportAction::class, data: [ExportActionTranslationKey::FORMAT->value => $format->value]);

            Excel::assertDownloaded("/\w+\.{$format->extension()}/", static fn (ExportJob $job): bool => $job instanceof $exportJob);
        }
    }
}
