<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SubjectsImport;
use App\Models\Admin\Program;
use App\Imports\CurriculumImport;

class CurriculumController extends Controller
{
    // Upload Form
    public function create()
    {
        $programs = Program::orderBy('name')->get();
        return view('admin.courses.curriculum.uploadProgram', compact('programs'));
    }

    // Handle Excel Upload
    public function store(Request $request)
{
    $request->validate([
        'program_id' => 'required|exists:programs,id',
        'excel_file' => 'required|file|mimes:xlsx,xls',
    ]);

    Excel::import(
        new \App\Imports\SubjectsImport($request->program_id),
        $request->file('excel_file')
    );

    return back()->with('success', 'Curriculum uploaded successfully!');
}
}
