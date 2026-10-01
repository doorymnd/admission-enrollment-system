<?php

namespace App\Services;

use App\Models\Admin\Enrollment;

class SectionService
{
   
    public static function generate(
        int $course_id,
        int $year_level, 
        string $section_code,
        int $max_students_per_section,
        int $sections_per_year = 3
    ): string
    {

        $prefix = $year_level . $section_code;

        for ($i = 1; $i <= $sections_per_year; $i++) {
            $section = "{$prefix}-{$i}";

            $count = Enrollment::where('course_id', $course_id)
                ->where('year_level', $year_level) 
                ->where('section', $section)
                ->count();


            if ($count < $max_students_per_section) {
                return $section;
            }
        }

        // 2️⃣ Auto-create new sections if all are full
        $i = $sections_per_year + 1;

        while (true) {

            $section = $prefix . '-' . $i;

            $count = Enrollment::where('course_id', $course_id)
                ->where('year_level', $year_level)
                ->where('section', $section)
                ->count();

            if ($count < $max_students_per_section) {
                return $section;
            }

            $i++;
        
        
        }
    }

}