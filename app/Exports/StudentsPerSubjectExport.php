<?php

namespace App\Exports;

use App\Models\Admin\StudentSubject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsPerSubjectExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $section;
    protected $subjectCode;

    public function __construct($section, $subjectCode)
    {
        $this->section = $section;
        $this->subjectCode = $subjectCode;
    }

    public function collection()
    {
        return StudentSubject::with(['student.info', 'subject'])
            ->whereHas('student.enrollment', function ($q) {
                $q->where('section', $this->section);
            })
            ->whereHas('subject', function ($q) {
                $q->where('code', $this->subjectCode);
            })
            ->get()
            ->map(function ($ss) {
                return [
                    'student_id' => $ss->student->student_no ?? '',
                    'first_name' => $ss->student->info->first_name ?? '',
                    'last_name'  => $ss->student->info->last_name ?? '',
                    'year_level' => $ss->year_level ?? '',
                    'semester'   => $ss->semester ?? '',
                    'units'      => $ss->subject->units ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'First Name',
            'Last Name',
            'Year Level',
            'Semester',
            'Units',
        ];
    }

    // OPTIONAL: column widths
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
            'C' => 25,
            'D' => 15,
            'E' => 15,
            'F' => 10,
        ];
    }
}