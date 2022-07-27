<?php

namespace App\Domains\Admin\Tests\Feature\Admin;

use App\Application\Tests\TestCase;
use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Admin\Admin\Components\Actions\Export\Pages\ExportAction;
use App\Domains\Admin\Enums\Translation\Components\Actions\ExportActionTranslationKey;
use App\Domains\Admin\Enums\Translation\ExportFormat;
use Livewire\Livewire;

abstract class ExportTest extends TestCase
{
    /** @var class-string<ListRecords> */
    protected string $listRecords;

    /** @test */
    public function export_works_correctly(): void
    {
        foreach (ExportFormat::cases() as $format) {
            Livewire::test($this->listRecords)->callPageAction(ExportAction::class, data: [ExportActionTranslationKey::FORMAT->value => $format->value])->assertFileDownloaded();
        }
    }
}
