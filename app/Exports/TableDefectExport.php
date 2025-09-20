<?php

namespace App\Exports;

use App\Models\TableDefect;
use Carbon\Carbon;
use App\Models\TableDowntime;
use App\Models\TableProduction;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TableDefectExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query ?: TableDefect::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'FY-N',
            'Shift',
            'Line',
            'Group',
            'Reporter',
            'Model Year',
            'Model',
            'Item Name',
            'Defect Category',
            'Defect Name',
            'Qty-A',
            'Qty-B',
            'Area',
            'Bolster-1',
            'Bolster-2',
            'Bolster-3',
            'Bolster-4',
            'Coil Number',
            'Created At',
            'Updated At',
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            Carbon::parse($row->date)->format('d-M-Y'),
            $row->fy_n,
            $row->shift,
            $row->line,
            $row->group,
            $row->reporter,
            $row->model_year,
            $row->model,
            $row->item_name,
            $row->defect_category,
            $row->defect_name,
            $row->defect_qty_a,
            $row->defect_qty_b,
            $row->defect_area,
            $row->bolster_1,
            $row->bolster_2,
            $row->bolster_3,
            $row->bolster_4,
            $row->coil_no,
            $row->created_at,
            $row->updated_at,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A1:V1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FF3D00']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}
