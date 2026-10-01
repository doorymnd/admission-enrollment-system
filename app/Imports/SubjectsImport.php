<?php

namespace App\Imports;

use App\Models\Admin\Course;
use App\Models\Admin\Subject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToCollection, WithHeadingRow
{
    protected $programId;

    public function __construct($programId)
    {
        $this->programId = $programId;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {

                // ✅ Skip broken rows
                if (
                    empty($row['course_code']) ||
                    empty($row['subject_code']) ||
                    empty($row['subject_name']) ||
                    empty($row['units']) ||
                    empty($row['year_level']) ||
                    empty($row['semester'])
                ) {
                    continue;
                }

                // ✅ Create or get course WITH program_id
                $course = Course::firstOrCreate(
                    [
                        'program_id' => $this->programId,
                        'code'       => trim($row['course_code']),
                    ],
                    [
                        'name' => trim($row['course_name'] ?? $row['course_code']),
                    ]
                );

                // ✅ Create or update subject
                Subject::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'code'      => trim($row['subject_code']),
                    ],
                    [
                        'name'       => trim($row['subject_name']),
                        'units'      => (int) $row['units'],
                        'year_level' => (int) $row['year_level'],
                        'semester'   => (int) $row['semester'],
                    ]
                );
            }
        });
    }
}
