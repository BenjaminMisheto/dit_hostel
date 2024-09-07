<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users->map(function($user) {
            return [
                'Name' => $user->name,
                'Registration Number' => $user->registration_number,
                'Block' => $user->block->name ?? 'Not Available',
                'Floor' => $user->floor->floor_number ?? 'Not Available',
                'Room' => $user->room->room_number ?? 'Not Available',
                'Bed' => $user->bed->bed_number ?? 'Not Available',
                'Course' => $user->course,
                'Gender' => $user->gender,
                'Payment' => !empty($user->payment_status) ? 'Paid' : 'Not Paid',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Registration Number',
            'Block',
            'Floor',
            'Room',
            'Bed',
            'Course',
            'Gender',
            'Payment',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '007bff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style the whole sheet
        $sheet->getStyle('A1:I' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'DDDDDD'],
                ],
            ],
            'font' => [
                'size' => 12,
            ],
        ]);

        // Style the rows
        $sheet->getStyle('A2:I' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // Set the width of columns
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);

        // Style Gender column (H)
        $sheet->getStyle('H2:H' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'color' => [
                    'argb' => '000000', // Default color
                ],
            ],
        ]);

        // Style Payment column (I)
        $sheet->getStyle('I2:I' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'color' => [
                    'argb' => '000000', // Default color
                ],
            ],
        ]);
    }
}
