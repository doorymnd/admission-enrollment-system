<?php

namespace App\Http\Controllers\Faculty\FacultyFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\FacultyAssignment;
use App\Models\Admin\SchoolSetting;

class FacultyMySubjectsController extends Controller
{
    public function mySubjects()
    {
        $facultyId = auth('faculty')->id(); 

        $currentSemester = SchoolSetting::first()->semester ?? 1;

        $assignments = FacultyAssignment::with('subject')
            ->where('faculty_id', $facultyId)
            ->where('semester', $currentSemester)
            ->get();

        return view('faculty.mySubjects', compact('assignments', 'currentSemester'));
    }
}
