<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentPromotionService; 
use App\Models\Admin\Course; 

class AdminController extends Controller
{
    public function viewStudent()
    {
        return view('admin.student.manageStudents');
    }

    public function promoteStudents()
    {
        // Get all courses
        $courses = Course::all();

        foreach ($courses as $course) {
            // Promote first-year students for this course
            StudentPromotionService::promoteYear($course->id, 1);
        }

        return redirect()->back()->with('success', 'All first-year students promoted successfully!');
    }
}
