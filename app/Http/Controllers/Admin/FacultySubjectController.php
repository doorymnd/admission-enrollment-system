<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Faculty;
use App\Models\Admin\Subject;

class FacultySubjectController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all();
        $subjects = Subject::all();
        

        return view('admin.subject.assignSubjects', compact('faculties','subjects'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        $faculty = Faculty::findOrFail($request->faculty_id);

        // Sync subjects (will replace old ones)
        $faculty->subjects()->sync($request->subjects);

        return redirect()->back()->with('success','Subjects assigned successfully!');
    }
}
