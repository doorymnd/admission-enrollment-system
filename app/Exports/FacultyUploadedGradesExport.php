<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FacultyUploadedGradesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $assignment_id;

    public function __construct($assignment_id)
    {
        $this->assignment_id = $assignment_id;
    }

    public function collection()
    {
        return Grade::with(['student.info', 'subject'])
            ->where('assignment_id', $this->assignment_id)
            ->get()
            ->map(function ($grade) {
                return [
                    'Student No' => $grade->student->student_no,
                    'First Name' => $grade->student->info->first_name ?? '',
                    'Last Name' => $grade->student->info->last_name ?? '',
                    'Subject' => $grade->subject->name,
                    'Raw Grade' => $grade->raw_grade,
                    'Final Grade' => $grade->final_grade,
                    'Remarks' => $this->getRemarks($grade->final_grade),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Student Number',
            'First Name',
            'Last Name',
            'Subject',
            'Raw Grade',
            'Final Grade',
            'Remarks'
        ];
    }

    public function columnWidths(): array
    {
        return[
            'A' => 35,
            'B' => 25, 
            'C' => 25,
            'D' => 40,
            'E' => 25,
            'F' => 25,
            'G' => 25,
        ];
    }

    private function getRemarks($grade)
    {
        if ($grade <= 3) return 'Passed';
        if ($grade == 4) return 'Incomplete';
        if ($grade == 5) return 'Failed';
        return '-';
    }
}
