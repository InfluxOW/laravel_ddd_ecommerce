<?php

namespace App\Domains\Generic\Jobs;

use App\Domains\Generic\Classes\Excel\ExportColumn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class ExportJob implements FromQuery, WithStrictNullComparison, WithCustomQuerySize, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    protected Collection $recordsIds;

    private string $sortColumn = 'id';

    private bool $sortByDesc = false;

    private const DEFAULT_FONT = 'Times New Roman';

    private const DEFAULT_ALIGNMENT = ['horizontal' => 'center', 'vertical' => 'center'];

    public function query(): Builder
    {
        $query = $this->getBaseQuery();

        $this->narrowQuery($query);

        $this->sortQuery($query);

        return $query;
    }

    public function querySize(): int
    {
        return 200;
    }

    public function setRecordsIds(Collection $recordsIds): static
    {
        $this->recordsIds = $recordsIds;

        return $this;
    }

    public function setSortColumn(?string $column): static
    {
        if ($column === null) {
            return $this;
        }

        /** @phpstan-ignore-next-line */
        if (in_array($column, $this->getBaseQuery()->newModelInstance()->getColumns(), true)) {
            $this->sortColumn = $column;
        }

        return $this;
    }

    public function setSortDirection(?string $sortDirection): static
    {
        if ($sortDirection === null) {
            return $this;
        }

        $this->sortByDesc = ($sortDirection === 'desc');

        return $this;
    }

    public function styles(Worksheet $sheet): array
    {
        $styles = [];

        // Columns
        foreach (array_keys($this->columnFormats()) as $column) {
            $styles[$column] = ['font' => ['bold' => false, 'size' => 11, 'name' => self::DEFAULT_FONT], 'alignment' => self::DEFAULT_ALIGNMENT];
        }

        // Heading
        $styles[1] = ['font' => ['bold' => true, 'size' => 16, 'name' => self::DEFAULT_FONT], 'alignment' => self::DEFAULT_ALIGNMENT];

        return $styles;
    }

    /**
     * @param Model $row
     */
    public function map(mixed $row): array
    {
        return $this->rows()
            ->map(static function (ExportColumn $exportRow) use ($row): mixed {
                $value = $exportRow->value($row);

                if ($value instanceof Carbon) {
                    $value = Date::dateTimeToExcel($value);
                }

                if (is_bool($value)) {
                    $value = $value ? '✓' : '❌';
                }

                return $value;
            })->toArray();
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return $this->rows()->map(static fn (ExportColumn $exportRow): string => $exportRow->heading())->toArray();
    }

    public function columnFormats(): array
    {
        $range = range('A', 'Z');

        $formats = [];
        foreach ($this->rows() as $i => $row) {
            $formats[$range[$i]] = $row->format();
        }

        return $formats;
    }

    /**
     * @return Collection<int,ExportColumn>
     */
    abstract protected function rows(): Collection;

    abstract protected function getBaseQuery(): Builder;

    private function narrowQuery(Builder $query): void
    {
        $query->when(isset($this->recordsIds), fn (Builder $query) => $query->whereIntegerInRaw('id', $this->recordsIds));
    }

    protected function sortQuery(Builder $query): void
    {
        $this->sortByDesc ? $query->orderByDesc($this->sortColumn) : $query->orderBy($this->sortColumn);
    }
}
