<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Program;
use App\Models\Admin\Subject;
use App\Models\Admin\Faculty;
use App\Models\Admin\SubjectAssignment;
use Illuminate\Http\Request;

class SubjectAssignmentController extends Controller
{
    public function index()
    {
        $departments = Program::select('department')->distinct()->get();
        $faculties = Faculty::where('status', 'active')->get();

        return view('admin.assignSubject', compact('departments','faculties'));
    }

    public function getCourses($department)
    {
        return Program::where('department', $department)->get();
    }

    public function getSubjects($course_id)
    {
        return Subject::where('course_id', $course_id)->get();
    }

    public function assign(Request $request)
    {
        SubjectAssignment::create([
            'faculty_id' => $request->faculty_id,
            'subject_id' => $request->subject_id,
        ]);

        return back()->with('success','Subject Assigned Successfully!');
    }
}
