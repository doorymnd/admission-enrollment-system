<?php

namespace App\Imports;

use App\Models\Admin\Course;
use App\Models\Admin\Subject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CurriculumImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // 1️⃣ Find or create the course
            $course = Course::firstOrCreate(
                ['code' => $row['course_code']],
                ['name' => $row['course_name']]
            );

            // 2️⃣ Prepare prerequisites
            $prerequisites = isset($row['prerequisites']) && $row['prerequisites'] != ''
                ? $row['prerequisites']
                : null;

            // 3️⃣ Insert or update the subject
            Subject::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'code'      => $row['subject_code'],
                ],
                [
                    'name'        => $row['subject_name'],
                    'year_level'  => $row['year_level'],
                    'semester'    => $row['semester'], // 1,2,3
                    'units'       => $row['units'],
                    'prerequisites' => $prerequisites,
                ]
            );
        }
    }
}
