<?php

namespace App\Http\Controllers\Faculty\FacultyFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\StudentSubject;
use App\Models\Admin\FacultyAssignment;
use App\Models\Admin\SchoolSetting;
use App\Exports\StudentsPerSubjectExport;
use Maatwebsite\Excel\Facades\Excel;


class FacultyMyStudentsController extends Controller
{
    public function myStudents(Request $request)
{
    $facultyId = Auth::guard('faculty')->id();

    // ✅ GET ACTIVE SEMESTER
    $setting = SchoolSetting::first();
    $activeSemester = $setting->semester ?? null; // '1st' or '2nd'

    // Get assigned subjects
    $subjectIds = FacultyAssignment::where('faculty_id', $facultyId)
        ->pluck('subject_id');

    // Base query
    $query = StudentSubject::with([
        'student.enrollment',
        'student.info',
        'subject'
    ])->whereIn('subject_id', $subjectIds);

    // ✅ FILTER BY ACTIVE SEMESTER
    if ($activeSemester) {
        $query->where('semester', $activeSemester);
    }

    // ✅ APPLY CLASS FILTER
    if ($request->class_filter) {
        [$section, $subject] = explode('|', $request->class_filter);

        $query->whereHas('student.enrollment', function ($q) use ($section) {
            $q->where('section', $section);
        })->whereHas('subject', function ($q) use ($subject) {
            $q->where('code', $subject);
        });
    }

    $data = $query->get();

    // ✅ DROPDOWN OPTIONS (ALSO FILTERED BY SEMESTER)
    $classOptions = StudentSubject::with(['student.enrollment', 'subject'])
        ->whereIn('subject_id', $subjectIds)
        ->when($activeSemester, function ($q) use ($activeSemester) {
            $q->where('semester', $activeSemester);
        })
        ->get()
        ->map(function ($ss) {
            $section = $ss->student->enrollment->section ?? 'N/A';
            $subject = $ss->subject->code ?? 'N/A';
            return $section . '|' . $subject;
        })
        ->unique()
        ->sort()
        ->values();

    return view('faculty.myStudents', compact('data', 'classOptions', 'activeSemester'));
}




   public function downloadStudents(Request $request)
    {
        $section = $request->section;
        $subject = $request->subject;

        return Excel::download(
            new StudentsPerSubjectExport($section, $subject),
            $section . '_' . $subject . '.xlsx'
        );
    }
}
