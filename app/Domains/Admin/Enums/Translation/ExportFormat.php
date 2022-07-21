<?php

namespace App\Domains\Admin\Enums\Translation;

enum ExportFormat: string
{
    case HTML = 'Html'; // Excel::HTML
    case CSV = 'Csv'; // Excel::CSV
    case XLSX = 'Xlsx'; // Excel::XLSX

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
