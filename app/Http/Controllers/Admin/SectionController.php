<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;


class SectionController extends Controller
{
    public function assignSections()
    {
        // Define rules per course/program
        $sectionRules = [
            'BSE-Eng' => ['prefix' => '1b1', 'years' => [1 => 3, 2 => 2, 3 => 2, 4 => 1]],
            'BSIT' => ['prefix' => 'IT', 'years' => [1 => 2, 2 => 2, 3 => 1, 4 => 1]],

        ];

        // Get all passed students
        $passedStudents = ExamResult::where('status', 'passed')->get();

        foreach ($passedStudents as $exam) {
            $enrollment = Enrollment::where('student_id', $exam->student_id)->first();
            if (!$enrollment) continue;

            $programCode = $enrollment->course; // You are storing course code in enrollment->course
            $year = (int) $enrollment->year_level; // convert string '1st' to int if needed

            if (!isset($sectionRules[$programCode])) continue;

            $rule = $sectionRules[$programCode];
            $prefix = $rule['prefix'];
            $totalSections = $rule['years'][$year] ?? 1;

            // Round-robin assignment
            $sectionNumber = ($enrollment->id % $totalSections) + 1;
            $enrollment->section = $prefix . '-' . $sectionNumber;
            $enrollment->save();
        }

        return "Sections assigned successfully!";
    }
}
