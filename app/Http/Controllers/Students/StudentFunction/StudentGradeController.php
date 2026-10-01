<?php

namespace App\Http\Controllers\Students\StudentFunction;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Enrollment;
use App\Models\Admin\Grade;
use App\Models\Admin\Subject;
use App\Models\Admin\StudentSubject;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentGradeController extends Controller
{
    public function viewGrades()
    {
        $grades = Grade::with(['student', 'subject', 'faculty'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('subject_id');

        return view('faculty.grades.viewUploadedGrades', compact('grades'));
    }

    public function index()
    {
        $student = Auth::guard('student')->user();

        $semesters = StudentSubject::where('student_id', $student->id)
        ->select('school_year', 'semester')
        ->distinct()
        ->orderBy('semester', 'desc')
        ->orderByRaw("
            CASE semester
                WHEN 1 THEN 1
                WHEN 2 THEN 2
                WHEN 3 THEN 3
            END
        ")
        ->get();

        return view('student.viewStudentFunction.semGrades', compact('semesters'));
    }

    public function showGrades($school_year, $semester)
    {
        $student = Auth::guard('student')->user();

        $subjects = StudentSubject::with('subject')
            ->where('student_id', $student->id)
            ->where('school_year', $school_year)
            ->where('semester', $semester)
            ->get();

        $grades = Grade::where('student_id', $student->id)
            ->where('school_year', $school_year)
            ->where('semester', $semester)
            ->get()
            ->keyBy('subject_id');

        return view('student.viewStudentFunction.myGrades', compact(
            'subjects',
            'grades',
            'school_year',
            'semester'
        ));
    }




    public function downloadGrades($school_year, $semester)
    {
        try {
            $student = Auth::guard('student')->user()->load([
                'info',
                'enrollments.courseInfo' // IMPORTANT FIX
            ]);

            $subjects = StudentSubject::with('subject')
                ->where('student_id', $student->id)
                ->where('school_year', $school_year)
                ->where('semester', $semester)
                ->get();

            $grades = Grade::where('student_id', $student->id)
                ->where('school_year', $school_year)
                ->where('semester', $semester)
                ->get()
                ->keyBy('subject_id');

            $pdf = Pdf::loadView('student.viewStudentFunction.grades_pdf', compact(
                'subjects',
                'grades',
                'school_year',
                'semester',
                'student'
            ));

            return $pdf->download('MyGrades.pdf');

        } catch (\Exception $e) {
            dd($e->getMessage()); // SHOW REAL ERROR
        }
    }

}