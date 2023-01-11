<?php

namespace App\Domains\Admin\Admin\Components\Actions\Export;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Admin\Enums\Translation\Components\Actions\ExportActionTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Enums\Translation\ExportFormat;
use App\Domains\Common\Interfaces\Exportable;
use App\Domains\Common\Jobs\ExportJob;
use App\Domains\Common\Utils\LangUtils;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait HasExportAction
{
    /**
     * @param class-string<Model & Exportable> $model
     */
    public static function create(string $model): static
    {
        return static::makeTranslated(static::getActionTranslationKey())
            ->action(function (Collection $records, Page $livewire, array $data) use ($model): BinaryFileResponse {
                /** @var ExportFormat $format */
                $format = ExportFormat::tryFrom($data[ExportActionTranslationKey::FORMAT->value]);
                $filename = Str::of($model)->classBasename()->plural()->snake()->lower()->value();

                /** @var ExportJob $job */
                $job = new ($model::getExportJob())();

                if ($records->isNotEmpty()) {
                    $job->setRecordsIds($records->pluck('id'));
                } elseif ($livewire instanceof EditRecord || $livewire instanceof ViewRecord) {
                    $recordId = $livewire->getRecord()->getKey();
                    $job->setRecordsIds(collect($recordId));
                    $filename = Str::of($model)->classBasename()->snake()->lower()->append("_{$recordId}")->value();
                }

                if ($livewire instanceof ListRecords) {
                    $job
                        ->setSortColumn($livewire->getTableSortColumn())
                        ->setSortDirection($livewire->getTableSortDirection());
                }

                $file = "{$filename}.{$format->extension()}";

                return Excel::download($job, $file, $format->value, [
                    'Content-Type' => $format->contentType(),
                    'Content-Disposition' => sprintf('inline; filename="%s"', $file),
                ]);
            })
            ->form([
                Select::makeTranslated(ExportActionTranslationKey::FORMAT)
                    ->options(function (): array {
                        $formats = [];
                        foreach (ExportFormat::cases() as $format) {
                            $formats[$format->value] = LangUtils::translateEnum($format);
                        }

                        return $formats;
                    })
                    ->required(),
            ])
            ->requiresConfirmation()
            ->icon('heroicon-o-download')
            ->color('success');
    }

    public static function getDefaultName(): ?string
    {
        return static::getActionTranslationKey()->value;
    }

    abstract protected static function getActionTranslationKey(): AdminActionTranslationKey;
}
