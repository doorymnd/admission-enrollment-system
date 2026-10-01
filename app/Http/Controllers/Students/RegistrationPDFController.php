<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Enrollment;
use App\Models\Admin\StudentSubject;
use App\Models\Admin\ClassSchedule;

class RegistrationPDFController extends Controller
{
    // SHOW ALL ENROLLMENT RECORDS
    public function registrationList()
    {
        $studentId = Auth::guard('student')->id();

        $enrollments = Enrollment::where('student_id', $studentId)
            ->orderBy('school_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('student.viewStudentFunction.registrationFormPDF', compact('enrollments'));
    }

    // DOWNLOAD PDF PER ENROLLMENT
    public function download($id)
{
    $studentId = Auth::guard('student')->id();

    // ENROLLMENT
    $enrollment = Enrollment::where('id', $id)
        ->where('student_id', $studentId)
        ->firstOrFail();

    /*
    |--------------------------------------
    | ENROLLED SUBJECTS (NORMAL LOAD)
    |--------------------------------------
    */
    $subjects = StudentSubject::with('subject')
        ->where('student_id', $studentId)
        ->where('enrollment_id', $enrollment->id)
        ->where('is_added', 0)
        ->get();

    /*
    |--------------------------------------
    | ADDED / IRREGULAR SUBJECTS
    |--------------------------------------
    */
    $addedSubjects = StudentSubject::with('subject')
        ->where('student_id', $studentId)
        ->where('enrollment_id', $enrollment->id)
        ->where('is_added', 1)
        ->get();


    $schedules = ClassSchedule::with(['subject', 'faculty'])
    ->where('course_id', $enrollment->course_id)
    ->where('semester', $enrollment->semester)
    ->get();

    // map for fast lookup by subject_id
    $scheduleMap = $schedules->keyBy('subject_id');

    /*
    |--------------------------------------
    | PDF GENERATION
    |--------------------------------------
    */
    $pdf = Pdf::loadView(
        'student.viewStudentFunction.downloadRegFormPDF',
        compact('enrollment', 'subjects', 'addedSubjects','scheduleMap')
    )->setPaper('A4', 'portrait');

    return $pdf->download(
        'registration_form_' . $enrollment->school_year . '_sem' . $enrollment->semester . '.pdf'
    );
}
}