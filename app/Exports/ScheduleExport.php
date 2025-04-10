<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleExport implements FromView, WithStyles
{
    public function __construct(public array $data, public array $times) {}

    public function view(): View
    {
        return view('schedule.excel', [
            'data' => $this->data,
            'times' => $this->times,
        ]);
    }

    /**
     * Apply styles to specific rows or columns
     */
    public function styles(Worksheet $sheet)
    {
        // Apply style to the first row (headers)
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // white
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF005A5A'], // dark teal
            ],
        ]);

        // Apply style to the second and third row headers
        $sheet->getStyle('A2:Z4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF007070'],
            ],
        ]);

        // Optional: Auto-size columns
        foreach (range('A', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
