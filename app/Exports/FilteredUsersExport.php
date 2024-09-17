<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class FilteredUsersExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $checkinCheckout;
    protected $semester;

    public function __construct($data, $checkinCheckout, $semester)
    {
        $this->data = $data;
        $this->checkinCheckout = $checkinCheckout;
        $this->semester = $semester;
    }

    public function array(): array
    {
        $formattedData = [];
        $currentIndex = 1;

        if ($this->checkinCheckout === 'checkin') {
            foreach ($this->data as $user) {
                $checkoutItems = json_decode($user['checkout_items_names'], true);

                if ($checkoutItems) {
                    foreach ($checkoutItems as $item) {
                        $formattedData[] = [
                            '#' => $currentIndex++,
                            'Name' => $user['user']['name'] ?? 'N/A',
                            'Reg No' => $user['user']['registration_number'] ?? 'N/A',
                            'Course' => $user['course_name'] ?? 'N/A',
                            'Gender' => $user['user']['gender'] ?? 'N/A',
                            'Semester' => $this->semester,
                            'Checkin' => 'checkin', // Indicating check-in
                            'Items' => $item['name'] ?? 'Not Available',
                            'Condition' => $item['condition'] ?? 'Not Available',
                        ];
                    }
                } else {
                    $formattedData[] = [
                        '#' => $currentIndex++,
                        'Name' => $user['user']['name'] ?? 'N/A',
                        'Reg No' => $user['user']['registration_number'] ?? 'N/A',
                        'Course' => $user['course_name'] ?? 'N/A',
                        'Gender' => $user['user']['gender'] ?? 'N/A',
                        'Semester' => $this->semester,
                        'Checkin' => 'checkin', // Indicating check-in
                        'Items' => 'Not Available',
                        'Condition' => 'Not Available',
                    ];
                }
            }
        } elseif ($this->checkinCheckout === 'checkout') {
            foreach ($this->data as $userId => $checkouts) {
                $user = $checkouts[0]['user'];
                $groupedCheckouts = [];

                foreach ($checkouts as $checkout) {
                    $groupedCheckouts[$checkout['name']][] = $checkout;
                }

                foreach ($groupedCheckouts as $itemName => $checkoutsGroup) {
                    foreach ($checkoutsGroup as $checkout) {
                        $formattedData[] = [
                            '#' => $currentIndex++,
                            'Name' => $user['name'] ?? 'N/A',
                            'Reg No' => $user['registration_number'] ?? 'N/A',
                            'Course' => $checkouts[0]['course_name'] ?? 'N/A',
                            'Gender' => $user['gender'] ?? 'N/A',
                            'Semester' => $this->semester,
                            'Checkin' => 'checkOut', // Indicating checkout
                            'Items' => $itemName ?? 'Not Available',
                            'Condition' => $checkout['condition'] ?? 'Not Available',
                        ];
                    }
                }
            }
        }

        return $formattedData;
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Reg No',
            'Course',
            'Gender',
            'Semester',
            'Checkin',
            $this->checkinCheckout === 'checkin' ? 'Given Items' : 'Returned Items',
            'Condition',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling for the header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // White font color
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '007bff'], // Blue background
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'border' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Black border
                ]
            ]
        ];

        // Styling for general cells
        $cellStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'border' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Black border
                ],
            ],
        ];

        // Applying styles
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle); // Header row style
        $sheet->getStyle('A2:I' . ($sheet->getHighestRow()))->applyFromArray($cellStyle); // General cells style

        // Conditional styling for "Condition" column
        $rows = $sheet->getHighestRow();
        for ($row = 2; $row <= $rows; $row++) {
            $conditionCell = 'I' . $row;
            $conditionValue = $sheet->getCell($conditionCell)->getValue();

            if (stripos($conditionValue, 'Good') !== false) {
                $sheet->getStyle($conditionCell)->applyFromArray([
                    'font' => ['color' => ['rgb' => '008000']], // Green color for 'Good'
                ]);
            } elseif (stripos($conditionValue, 'Bad') !== false) {
                $sheet->getStyle($conditionCell)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'FF0000']], // Red color for 'Bad'
                ]);
            } else {
                $sheet->getStyle($conditionCell)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'FF0000']], // Red color for other cases
                ]);
            }
        }
    }
}
