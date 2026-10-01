<?php

namespace App\Services;

use App\Models\Admin\Student;
use App\Models\Admin\Course;
use App\Services\SectionService;

class StudentPromotionService
{
    /**
     * Promote all students in a course/year to next year and assign new section
     *
     * @param int $courseId
     * @param int $currentYear
     */
    public static function promoteYear($courseId, $currentYear)
    {
        $students = Student::where('course_id', $courseId)
            ->where('year_level', $currentYear)
            ->get();

        $course = Course::find($courseId);

        foreach ($students as $student) {

            $newYearLevel = $student->year_level + 1;

            // Generate new section for the next year
            $newSection = SectionService::generate(
                $course->id,
                $newYearLevel,
                $course->section_code,
                $course->max_students_per_section ?? 2, // default to 30 if not set
                $course->sections_per_year
            );

            // Update student year and section
            $student->update([
                'year_level' => $newYearLevel,
                'section'    => $newSection
            ]);

            // Create new enrollment record
            $student->enrollment()->create([
                'course_id'   => $course->id,
                'year_level'  => $newYearLevel,
                'section'     => $newSection,
                'status'      => 'Regular',
                'school_year' => now()->year . '-' . (now()->year + 1),
            ]);
        }
    }
}
