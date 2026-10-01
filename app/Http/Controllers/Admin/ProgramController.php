<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Program;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:programs,code',
            'name' => 'required|string',
        ]);

        Program::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.courses.coursesPanel')
            ->with('success', 'Course added successfully.');
    }
}
