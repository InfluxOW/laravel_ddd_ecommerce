<?php

namespace App\Domains\Admin\Enums\Translation;

use Maatwebsite\Excel\Excel;

enum ExportFormat: string
{
    case HTML = Excel::HTML;
    case CSV = Excel::CSV;
    case XLSX = Excel::XLSX;

    public function extension(): string
    {
        return strtolower($this->value);
    }

    public function contentType(): string
    {
        return match ($this) {
            self::HTML => 'text/html',
            self::CSV => 'text/csv',
            self::XLSX => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        };
    }
}
