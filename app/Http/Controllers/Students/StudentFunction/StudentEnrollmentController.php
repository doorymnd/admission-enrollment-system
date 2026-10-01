<?php

namespace App\Http\Controllers\Students\StudentFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Enrollment;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    public function viewEnrollments()
    {
        $student = Auth::guard('student')->user();

        // Get all enrollments of the student
        $enrollments = Enrollment::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current (latest approved/enrolled)
        $currentEnrollment = Enrollment::where('student_id', $student->id)
            ->whereIn('status', ['approved', 'enrolled', 'regular'])
            ->latest()
            ->first();

        return view('student.viewStudentFunction.enrollments', compact('enrollments', 'currentEnrollment'));
    }
}
