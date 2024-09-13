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
    protected $adminCheckouts;
    protected $block;

    public function __construct($users, $adminCheckouts, $block)
    {
        $this->users = $users;
        $this->adminCheckouts = $adminCheckouts;
        $this->block = $block;
    }

    public function collection()
    {
        $data = [];

        // Fetch and structure checkout data
        foreach ($this->adminCheckouts as $checkout) {
            $data[] = [
                'Item Name' => $checkout->name,
                'Condition' => $checkout->condition,
                'Block' => $checkout->user->block->name ?? 'Not Available',
                'Floor' => $checkout->user->floor->floor_number ?? 'Not Available',
                'Room' => $checkout->user->room->room_number ?? 'Not Available',
            ];
        }

        // Calculate the summary details
        $totalItems = $this->adminCheckouts->count();
        $goodItems = $this->adminCheckouts->where('condition', 'Good')->count();
        $badItems = $this->adminCheckouts->where('condition', 'Bad')->count();
        $noneItems = $this->adminCheckouts->where('condition', 'None')->count();
        $goodPercentage = $totalItems > 0 ? ($goodItems / $totalItems) * 100 : 0;
        $badPercentage = $totalItems > 0 ? ($badItems / $totalItems) * 100 : 0;
        $nonePercentage = $totalItems > 0 ? ($noneItems / $totalItems) * 100 : 0;

        // Append the summary section to the data
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
        // Style for the header row
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType('solid')->getStartColor()->setARGB('FF007bff'); // Blue header background
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF'); // White font for header

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(25);

        // Set alignment
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

                // Remove borders for all rows except the header
                $totalRows = count($this->adminCheckouts) + 6; // Calculate total rows with summary
                $sheet->getStyle('A2:E' . $totalRows)->getBorders()->getAllBorders()->setBorderStyle('none');

                // Highlight important sections (summary rows)
                $summaryStartRow = count($this->adminCheckouts) + 2; // First row of the summary
                $sheet->getStyle('A' . $summaryStartRow . ':C' . ($summaryStartRow + 4));
                    // ->getFill()->setFillType('solid')
                    // ->getStartColor()->setARGB('FFFAD02E');

                $sheet->getStyle('A' . $summaryStartRow . ':C' . ($summaryStartRow + 4))
                    ->getFont()->setBold(true); // Bold font for the summary

                // Set bold text and different color for "Bad" condition
                foreach ($this->adminCheckouts as $index => $checkout) {
                    if ($checkout->condition === 'Bad') {
                        $rowNumber = $index + 2; // Adjust row number (data starts from row 2)
                        $sheet->getStyle('B' . $rowNumber)
                            ->getFont()->getColor()->setARGB('FFFF0000'); // Red font for "Bad" condition
                        $sheet->getStyle('B' . $rowNumber)->getFont()->setBold(true); // Bold text for emphasis
                    }
                }
            },
        ];
    }
}
