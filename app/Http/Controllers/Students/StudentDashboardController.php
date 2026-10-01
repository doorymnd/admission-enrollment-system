<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\SchoolSetting;
use App\Models\Admin\Subject;
use App\Models\Admin\Enrollment;

class StudentDashboardController extends Controller
{
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        $setting = SchoolSetting::first();

        $student->load([
            'info',
            'enrollments.courseInfo'
        ]);

        // =========================
        // CURRENT ENROLLMENT
        // =========================
        $latestEnrollment = $student->enrollments()
            ->orderByDesc('year_level')
            ->orderByDesc('semester')
            ->first();

        $isEnrolledThisSemester = Enrollment::where('student_id', $student->id)
            ->where('school_year', $setting->school_year)
            ->where('semester', $setting->semester)
            ->exists();

        // =========================
        // IRREGULAR STATUS (IMPORTANT FIX)
        // =========================
        $irregularStatus = DB::table('irregular_requests')
            ->where('student_id', $student->id)
            ->where('semester', $setting->semester)
            ->where('school_year', $setting->school_year)
            ->value('status'); // pending / approved / rejected / null

        // =========================
        // SUMMER CHECK
        // =========================
        $isSecondSem = $latestEnrollment && $latestEnrollment->semester == 2;

        $hasMandatorySummer = false;

        if ($latestEnrollment) {
            $hasMandatorySummer = Subject::where('course_id', $latestEnrollment->course_id)
                ->where('semester', 3)
                ->exists();
        }

        $hasSummerEnrollment = false;

        if ($latestEnrollment) {
            $hasSummerEnrollment = Enrollment::where('student_id', $student->id)
                ->where('year_level', $latestEnrollment->year_level)
                ->where('semester', 3)
                ->exists();
        }

        $showMidyearButton =
            $isSecondSem
            && $hasMandatorySummer
            && !$hasSummerEnrollment;

        return view('student.studentDash', compact(
            'student',
            'setting',
            'latestEnrollment',
            'isEnrolledThisSemester',
            'irregularStatus',
            'showMidyearButton'
        ));
    }
}