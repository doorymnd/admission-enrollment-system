<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Enrollment;
use Illuminate\Support\Facades\Auth;

class EnrollmentPromoteController extends Controller
{
    // =========================
    // REQUEST MIDYEAR (STUDENT SIDE)
    // =========================
    public function enrollSummer(Request $request)
    {
        $student = Auth::guard('student')->user();

        // get latest 2nd sem enrollment
        $latest = Enrollment::where('student_id', $student->id)
            ->where('semester', 2)
            ->orderByDesc('id')
            ->first();

        if (!$latest) {
            return back()->with('error', 'No 2nd semester record found.');
        }

        if ($latest->status == 'pending') {
            return back()->with('error', 'Already requested Mid Year.');
        }

        // ONLY update current record
        $latest->update([
            'status' => 'pending'
        ]);

        return back()->with('success', 'Enrollments sent successfuly! Wait for admin approval.');
    }

    // =========================
    // OPTIONAL: set pending manually
    // =========================

        public function setPending($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $enrollment->status = 'pending';
        $enrollment->save();

        return back()->with('success', 'Enrollment is on process, wait for admin approval!');
    }
}