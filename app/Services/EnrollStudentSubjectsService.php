<?php

namespace App\Services;

use App\Models\StudentSubject;
use App\Models\Admin\Subject;

class EnrollStudentSubjectsService
{
    public static function assign($enrollment)
    {
        $studentId = $enrollment->student_id;
        $courseId = $enrollment->course_id;
        $yearLevel = $enrollment->year_level;

        // Get subjects for course + year level
        $subjects = Subject::where('course_id', $courseId)
            ->where('year_level', $yearLevel)
            ->get();

        foreach ($subjects as $subject) {

            StudentSubject::firstOrCreate([
                'student_id' => $studentId,
                'subject_id' => $subject->id
            ]);
        }
    }
}