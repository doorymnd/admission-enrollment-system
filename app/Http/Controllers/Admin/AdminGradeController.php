<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Grade;
use App\Models\Admin\Enrollment;
use App\Services\StudentService;

class AdminGradeController extends Controller
{
    public function pending()
    {
        $grades = Grade::where('status', 'pending')->get();
        return view('admin.grades.pending', compact('grades'));
    }

    public function approve($id)
    {
        $grade = Grade::findOrFail($id);

        $grade->update([
            'status' => 'released',
            'approved_at' => now(),
            'approved_by' => auth()->user()->id,
        ]);
        
        StudentService::updateStudentType($grade->student_id);
        

        return back()->with('success', 'Grade approved.');
    }

    public function reject($id)
    {
        Grade::where('id', $id)->update(['status' => 'rejected']);
        return back()->with('error', 'Grade rejected.');
    }

}
