<?php

namespace App\Exports;

use App\Models\Admission\Admission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExamTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Admission::where('status', 'pending')
            
            ->get()
            ->map(function ($item) {
                return [
                    'email' => $item->email,
                    'primary_course' => '',
                    'secondary_course' => '',
                    'tertiary_course' => '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'email',
            'primary_result',
            'secondary_result',
            'tertiary_result'
        ];
    }

    // COLUMN WIDTH
    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 25, 
            'C' => 25,
            'D' => 25, 
        ];
    }
}