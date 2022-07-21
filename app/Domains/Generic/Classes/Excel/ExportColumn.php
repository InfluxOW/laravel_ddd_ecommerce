<?php

namespace App\Domains\Generic\Classes\Excel;

use App\Domains\Generic\Utils\LangUtils;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class ExportColumn
{
    private function __construct(private BackedEnum $key, private string $format)
    {
    }

    public static function create(BackedEnum $key, string $format = NumberFormat::FORMAT_TEXT): self
    {
        return new self($key, $format);
    }

    public function value(Model $model): mixed
    {
        return Arr::get($model, $this->key->value);
    }

    public function heading(): string
    {
        /** @var string $translation */
        $translation = LangUtils::translateEnum($this->key);

        return $translation;
    }

    public function format(): string
    {
        return $this->format;
    }
}
