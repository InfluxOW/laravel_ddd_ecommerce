<?php

namespace App\Domains\Admin\Admin\Components\Actions\Export;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Admin\Enums\Translation\Components\Actions\ExportActionTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Enums\Translation\ExportFormat;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminActionsModals;
use App\Domains\Generic\Interfaces\Exportable;
use App\Domains\Generic\Jobs\ExportJob;
use App\Domains\Generic\Utils\LangUtils;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @internal
 * */
trait HasExportAction
{
    use HasTranslatableAdminActionsModals;

    /**
     * @param class-string<Model & Exportable> $model
     */
    public static function create(string $model): static
    {
        /** @var static $action */
        $action = self::setTranslatableModal(self::makeTranslated(static::getActionTranslationKey())
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
            ->color('success'));

        return $action;
    }

    public static function getDefaultName(): ?string
    {
        return static::getActionTranslationKey()->value;
    }

    abstract protected static function getActionTranslationKey(): AdminActionTranslationKey;
}
