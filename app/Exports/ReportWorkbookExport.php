<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportWorkbookExport implements Export, WithMultipleSheets
{
    /**
     * Data seluruh sheet workbook.
     *
     * Format:
     * [
     *     [
     *         'title' => 'Nama Sheet',
     *         'headings' => [...],
     *         'rows' => [...],
     *     ],
     * ]
     *
     * @param array<int, array{
     *     title: string,
     *     headings: array,
     *     rows: array
     * }> $sheets
     */
    public function __construct(private array $sheets)
    {
    }

    /**
     * Menghasilkan seluruh sheet Excel.
     *
     * @return array<int, ReportArraySheet>
     */
    public function sheets(): array
    {
        return array_map(
            function (array $sheet): ReportArraySheet {
                return new ReportArraySheet(
                    $sheet['title'],
                    $sheet['headings'],
                    $sheet['rows']
                );
            },
            $this->sheets
        );
    }
}