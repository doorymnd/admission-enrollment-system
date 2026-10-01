<?php

namespace App\Imports;

use App\Models\Admin\Grade;
use App\Models\Admin\Student;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class GradesImport implements ToCollection
{
    protected $subject_id;
    protected $course;
    protected $section;
    protected $year;
    protected $semester;
    protected $school_year;
    protected $faculty_id;
    protected $assignment_id;

    public function __construct(
        $subject_id,
        $course,
        $section,
        $year,
        $semester,
        $school_year,
        $faculty_id,
        $assignment_id
    ) {
        $this->subject_id = $subject_id;
        $this->course = $course;
        $this->section = $section;
        $this->year = $year;
        $this->semester = $semester;
        $this->school_year = $school_year;
        $this->faculty_id = $faculty_id;
        $this->assignment_id = $assignment_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $studentNo = trim($row[0] ?? '');
            $rawInput  = trim(strtoupper($row[1] ?? ''));

            if ($studentNo === '' || $rawInput === '') continue;

            $student = Student::whereRaw(
                'LOWER(student_no) = ?',
                [strtolower($studentNo)]
            )->first();

            if (!$student) continue;

            // 🔥 DEFAULT VALUES
            $raw_grade = null;
            $final_grade = null;

            // 🔥 HANDLE TEXT INPUTS
            if (in_array($rawInput, ['FAILED', 'FAIL', 'F'])) {
                $raw_grade = 0;
                $final_grade = 5.00;
            }
            elseif (in_array($rawInput, ['INC', 'INCOMPLETE'])) {
                $raw_grade = null;
                $final_grade = null;
            }
            else {
                $raw_grade = (float) $rawInput;
                $final_grade = $this->convertGrade($raw_grade);
            }

            Grade::create([
                'student_id' => $student->id,
                'assignment_id' => $this->assignment_id,
                'subject_id' => $this->subject_id,
                'faculty_id' => $this->faculty_id,
                'course' => $this->course,
                'section' => $this->section,
                'year_level' => $this->year,
                'semester' => $this->semester,
                'school_year' => $this->school_year,
                'raw_grade' => $raw_grade,
                'final_grade' => $final_grade,
                'status' => 'pending'
            ]);
        }
    }

    private function convertGrade($grade)
    {
        if ($grade >= 98) return 1.00;
        if ($grade >= 95) return 1.25;
        if ($grade >= 92) return 1.50;
        if ($grade >= 89) return 1.75;
        if ($grade >= 86) return 2.00;
        if ($grade >= 83) return 2.25;
        if ($grade >= 80) return 2.50;
        if ($grade >= 77) return 2.75;
        if ($grade >= 75) return 3.00;

        return 5.00;
    }
}