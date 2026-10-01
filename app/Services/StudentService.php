<?php

namespace App\Services;

use App\Models\Admin\Grade;
use App\Models\Admin\Enrollment;
use App\Models\Admin\Subject;
use Illuminate\Support\Facades\DB;

class StudentService
{
    // =========================
    // IRREGULAR CHECK
    // =========================
    public static function isIrregular($studentId, $section, $semester)
    {
        return Grade::where('student_id', $studentId)
            ->where('section', $section)
            ->where('semester', $semester)
            ->where('status', 'released')
            ->where('final_grade', 5.00)
            ->exists();
    }

    public static function getStudentType($studentId, $section, $semester)
    {
        return self::isIrregular($studentId, $section, $semester)
            ? 'irregular'
            : 'regular';
    }

    // public static function updateStudentType($studentId, $section, $semester)
    // {
    //     $type = self::getStudentType($studentId, $section, $semester);

    //     DB::table('students')
    //         ->where('id', $studentId)
    //         ->update([
    //             'student_type' => $type,
    //             'updated_at'   => now(),
    //         ]);

    //     return $type;
    // }

public static function updateStudentType($studentId)
{
    $hasFailed = Grade::where('student_id', $studentId)
        ->where('status', 'released')
        ->where('final_grade', 5.00)
        ->exists();

    $type = $hasFailed ? 'irregular' : 'regular';

    DB::table('students')
        ->where('id', $studentId)
        ->update([
            'student_type' => $type,
            'updated_at' => now(),
        ]);

    return $type;
}


    // =========================
    // SUMMER CHECKS
    // =========================
    public static function hasMandatorySummer($courseId)
    {
        return Subject::where('course_id', $courseId)
            ->where('semester', 3)
            ->exists();
    }

    public static function hasSummerEnrollment($studentId, $yearLevel)
    {
        return Enrollment::where('student_id', $studentId)
            ->where('year_level', $yearLevel)
            ->where('semester', 3)
            ->exists();
    }

    // =========================
    // PROMOTION CORE
    // =========================
    public static function promoteToNextYear($enrollment, $schoolYear, $sectionCallback)
    {
        return Enrollment::create([
            'student_id'  => $enrollment->student_id,
            'course_id'   => $enrollment->course_id,
            'course'      => $enrollment->course,
            'year_level'  => $enrollment->year_level + 1,
            'semester'    => 1,
            'section'     => $sectionCallback,
            'school_year' => $schoolYear,
            'status'      => 'approved',
            'type'        => 'regular',
        ]);
    }
}