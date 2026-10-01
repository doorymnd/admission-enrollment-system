<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Enrollment;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\SchoolSetting;

class EnrollmentController extends Controller
{
    public function requestEnroll()
{
    $student = Auth::guard('student')->user();

    $setting = SchoolSetting::first();

    if (!$setting) {
        return back()->with('error', 'School settings not configured.');
    }

    if ($setting->enrollment_status !== 'open') {
        return back()->with('error', 'Enrollment is closed.');
    }

    // ✅ Get current enrollment (same school year)
    $enrollment = Enrollment::where('student_id', $student->id)
        ->where('school_year', $setting->school_year)
        ->first();

    if (!$enrollment) {
        return back()->with('error', 'No enrollment record found.');
    }

    // ❌ Prevent duplicate update
    if ($enrollment->semester == 2) {
        return back()->with('error', 'Already in 2nd semester.');
    }

    if ($enrollment->status === 'pending') {
        return back()->with('error', 'You already submitted an enrollment request.');
    }

    // 🟡 ONLY MARK AS PENDING (NO SEMESTER CHANGE)
    $enrollment->status = 'pending';
    $enrollment->save();

    return back()->with('success', 'Enrollment request sent. Waiting for admin approval.');
}
}