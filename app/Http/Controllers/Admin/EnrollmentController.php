<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Student;
use App\Models\Admin\Subject;
use App\Models\Admin\Enrollment;
use App\Services\StudentService;
use App\Models\Admin\Grade;

use DB;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::all();

        $subjectsQuery = Subject::with('prerequisites');

        if ($request->year_level && $request->semester) {
            $subjectsQuery->where('year_level', $request->year_level)
                          ->where('semester', $request->semester);
        }

        $subjects = $subjectsQuery->get();

        return view('admin.enrollment.index', [
            'students' => $students,
            'subjects' => $subjects,
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required',
        'subjects' => 'required|array'
    ]);

    $hasFailed = Grade::where('student_id', $request->student_id)
        ->where('status', 'released')
        ->where('final_grade', 5.00)
        ->exists();

    $type = $hasFailed ? 'irregular' : 'regular';

    foreach ($request->subjects as $subject_id) {
        Enrollment::create([
            'student_id' => $request->student_id,
            'subject_id' => $subject_id,
            'type' => $type,
        ]);
    }
    StudentService::updateStudentType($request->student_id);

    return redirect()->back()->with('message', 'Enrollment successful!');
}

public function search(Request $request)
{
    $query = $request->q;

    return response()->json(
        Student::select('id','student_no','first_name','last_name','year_level','semester')
            ->where('student_no', 'like', "%{$query}%")
            ->orWhere('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->limit(10)
            ->get()
    );
}
}