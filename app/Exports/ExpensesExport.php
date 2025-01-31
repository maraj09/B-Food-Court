<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $totalAmount = 0;

    public function collection()
    {
        $expenses = Expense::all();
        $this->totalAmount = $expenses->sum('amount');
        return $expenses;
    }

    public function headings(): array
    {
        return [
            'Title',
            'Tags',
            'Amount',
            'Category',
            'Payment Mode',
            'Details',
            'Images'
        ];
    }

    public function map($expense): array
    {
        $tags = json_decode($expense->tags, true);
        $tagValues = is_array($tags) ? array_column($tags, 'value') : [];
        $images = $expense->images;

        // Generate clickable links for images
        $imageLinks = array_map(function ($image) {
            return asset($image); // Return URL for each image
        }, $images);

        // Join all image links into a single cell content
        $imageLinksString = implode(', ', $imageLinks);

        return [
            $expense->title,
            implode(', ', $tagValues),
            $expense->amount,
            $expense->expenseCategory->name ?? 'N/A',
            $expense->payment_mode,
            $expense->details,
            $imageLinksString, // Images column with URLs
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Calculate the total amount
                $totalAmount = 0;
                for ($row = 2; $row <= $highestRow; $row++) {
                    $amount = $sheet->getCellByColumnAndRow(3, $row)->getValue(); // Column 3 is 'C' (Amount column)
                    $totalAmount += (float) $amount;
                }

                // Add total amount row at the end
                $totalRow = $highestRow + 1;
                $sheet->setCellValueByColumnAndRow(1, $totalRow, 'Total');
                $sheet->setCellValueByColumnAndRow(3, $totalRow, $totalAmount);

                // Format the total amount row
                $sheet->getStyleByColumnAndRow(1, $totalRow, 7, $totalRow)->getFont()->setBold(true);

                // Handle hyperlinks for images
                for ($row = 2; $row <= $highestRow; $row++) {
                    $imageCellValue = $sheet->getCellByColumnAndRow(7, $row)->getValue(); // Column 7 is 'G' (Images column)

                    // Split the cell content by comma to handle multiple URLs
                    $urls = explode(', ', $imageCellValue);

                    foreach ($urls as $index => $url) {
                        if (filter_var($url, FILTER_VALIDATE_URL)) {
                            $fileName = basename($url);
                            $columnIndex = 7 + $index; // Starting from column 'G'

                            // Set the file name as the hyperlink text
                            $sheet->getCellByColumnAndRow($columnIndex, $row)->setValue($fileName);

                            // Set the hyperlink URL
                            $sheet->getCellByColumnAndRow($columnIndex, $row)->getHyperlink()->setUrl($url);
                            $sheet->getStyleByColumnAndRow($columnIndex, $row)->getFont()->setUnderline(true)->getColor()->setARGB(Color::COLOR_BLUE);
                        }
                    }
                }
            },
        ];
    }
}
