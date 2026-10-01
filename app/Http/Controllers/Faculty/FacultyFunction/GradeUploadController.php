<?php

namespace App\Http\Controllers\Faculty\FacultyFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GradesImport;
use App\Models\Admin\FacultyAssignment;
use App\Models\Admin\SchoolSetting;

class GradeUploadController extends Controller
{
    public function showForm()
    {
        $facultyId = auth()->id();

        $assignments = FacultyAssignment::where('faculty_id', $facultyId)
                        ->with('subject')
                        ->get();

        $schoolSetting = SchoolSetting::first();
        $currentSchoolYear = $schoolSetting ? $schoolSetting->school_year : '';

        return view('faculty.grades.upload', compact('assignments', 'currentSchoolYear'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required',
            'school_year' => 'required',
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $assignment = FacultyAssignment::where('id', $request->assignment_id)
            ->where('faculty_id', auth()->id())
            ->firstOrFail();

        Excel::import(new GradesImport(
            $assignment->subject_id,
            $assignment->course,
            $assignment->section,
            $assignment->year_level,
            $assignment->semester,
            $request->school_year,
            auth()->id(),
            $assignment->id
        ), $request->file('file'));

        return back()->with('success', 'Grades uploaded successfully!');
    }
}