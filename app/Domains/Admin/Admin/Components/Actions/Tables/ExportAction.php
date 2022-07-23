<?php

namespace App\Domains\Admin\Admin\Components\Actions\Tables;

use App\Domains\Admin\Admin\Abstracts\Actions\Tables\Action;
use App\Domains\Admin\Enums\Translation\Components\Actions\ExportActionTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Enums\Translation\ExportFormat;
use App\Domains\Generic\Interfaces\Exportable;
use App\Domains\Generic\Utils\LangUtils;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ExportAction extends Action
{
    /**
     * @param class-string<Model & Exportable> $model
     */
    public static function create(string $model): self
    {
        /** @var self $action */
        $action = self::setTranslatableModal(self::setTranslatableLabel(self::make(AdminActionTranslationKey::EXPORT->value)
            ->button()
            ->action(function (array $data) use ($model): BinaryFileResponse {
                /** @var ExportFormat $format */
                $format = ExportFormat::tryFrom($data[ExportActionTranslationKey::FORMAT->value]);
                $job = $model::getExportJob();
                $filename = Str::of($model)->classBasename()->plural()->snake()->lower()->value();
                $file = "{$filename}.{$format->extension()}";

                return Excel::download(new $job(), $file, $format->value, [
                    'Content-Type' => $format->contentType(),
                    'Content-Disposition' => sprintf('inline; filename="%s"', $file),
                ]);
            })
            ->form(self::setTranslatableLabels([
                Select::make(ExportActionTranslationKey::FORMAT->value)
                    ->options(function (): array {
                        $formats = [];
                        foreach (ExportFormat::cases() as $format) {
                            $formats[$format->value] = LangUtils::translateEnum($format);
                        }

                        return $formats;
                    })
                    ->required(),
            ], ExportActionTranslationKey::class))
            ->requiresConfirmation()
            ->icon('heroicon-o-download')
            ->color('success')));

        return $action;
    }

    public static function getDefaultName(): ?string
    {
        return AdminActionTranslationKey::EXPORT->value;
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return AdminActionTranslationKey::class;
    }
}
