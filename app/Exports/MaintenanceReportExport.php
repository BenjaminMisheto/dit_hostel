<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaintenanceReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithEvents
{
    protected $users;
    protected $type;
    protected $semesterId;

    public function __construct($users, $type, $semesterId)
    {
        $this->users = $users;
        $this->type = $type;
        $this->semesterId = $semesterId;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->users as $user) {
            $data[] = [
                'Item Name' => $user->name,
                'Condition' => $user->condition,
                'Block' => $user->block_name ?? 'Not Available',
                'Floor' => $user->floor_name ?? 'Not Available',
                'Room' => $user->bed_name ?? 'Not Available',
            ];
        }

        // Summary section
        $totalItems = $this->users->count();
        $goodItems = $this->users->where('condition', 'Good')->count();
        $badItems = $this->users->where('condition', 'Bad')->count();
        $noneItems = $this->users->where('condition', 'None')->count();

        $goodPercentage = $totalItems > 0 ? ($goodItems / $totalItems) * 100 : 0;
        $badPercentage = $totalItems > 0 ? ($badItems / $totalItems) * 100 : 0;
        $nonePercentage = $totalItems > 0 ? ($noneItems / $totalItems) * 100 : 0;

        $data[] = ['Condition' => 'Summary', 'Total Items' => ''];
        $data[] = ['Condition' => 'Good', 'Total Items' => $goodItems, 'Percentage' => number_format($goodPercentage, 2) . '%'];
        $data[] = ['Condition' => 'Bad', 'Total Items' => $badItems, 'Percentage' => number_format($badPercentage, 2) . '%'];
        $data[] = ['Condition' => 'None', 'Total Items' => $noneItems, 'Percentage' => number_format($nonePercentage, 2) . '%'];
        $data[] = ['Condition' => 'Total Items', 'Total Items' => $totalItems, 'Percentage' => ''];

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Condition',
            'Block',
            'Floor',
            'Room',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType('solid')->getStartColor()->setARGB('FF007bff');
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(25);

        $sheet->getStyle('A:E')->getAlignment()->setHorizontal('left');
    }

    public function title(): string
    {
        return 'Maintenance Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalRows = count($this->users) + 6;

                $sheet->getStyle('A2:E' . $totalRows)->getBorders()->getAllBorders()->setBorderStyle('none');
                $summaryStartRow = count($this->users) + 2;
                $sheet->getStyle('A' . $summaryStartRow . ':C' . ($summaryStartRow + 4))->getFont()->setBold(true);

                foreach ($this->users as $index => $user) {
                    if ($user->condition === 'Bad') {
                        $rowNumber = $index + 2;
                        $sheet->getStyle('B' . $rowNumber)->getFont()->getColor()->setARGB('FFFF0000');
                        $sheet->getStyle('B' . $rowNumber)->getFont()->setBold(true);
                    }
                }
            },
        ];
    }
}
