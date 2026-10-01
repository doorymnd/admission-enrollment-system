<?php

namespace App\Exports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OldStudentExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return new Collection([
            [
                'course' => '',
                'student_no' => '',
                'email' => '',
                'first_name' => '',
                'middle_name' => '',
                'last_name' => '',
                'suffix' => '',
                'phone' => '',
                'street' => '',
                'barangay' => '',
                'municipality' => '',
                'city' => '',
                'birth_date' => '',
                'sex' => '',
                'school_year' => '',
                'year_level' => '',
                'semester' => '',
                'section' => '',
                'status' => '',
            ]
        ]);
    }

    public function headings(): array
    {
        return[
            'course',
            'student_no',
            'email',
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'phone',
            'street',
            'barangay',
            'municipality',
            'city',
            'birth_date',
            'sex',
            'school_year',
            'year_level',
            'semester',
            'section',
            'status',
        ];
    }

    public function columnWidths(): array
    {
        return[
            'A' => 25,
            'B' => 25, 
            'C' => 25,
            'D' => 25,
            'E' => 25,
            'F' => 25, 
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 25, 
            'K' => 25,
            'L' => 25,
            'M' => 25,
            'N' => 25, 
            'O' => 25,
            'P' => 25,
            'Q' => 25,
            'R' => 25, 
            'S' => 25,

        ];
    }

}
