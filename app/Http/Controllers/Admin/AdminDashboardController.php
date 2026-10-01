<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Student;
use App\Models\Admission\Admission;
use App\Models\Admin\Faculty;
use App\Models\Admin\SchoolSetting;
use App\Models\Admin\StudentInfo;
class AdminDashboardController extends Controller
{
    public function adminDash()
    {
        $totalStudents = Student::count();
        $totalAdmission = Admission::count();
        $totalFaculty = Faculty::count();

        $setting = SchoolSetting::first();

        $requests = StudentInfo::with('student')
            ->whereIn('edit_request_status', ['pending', 'approved', 'rejected'])
            ->limit(5)
            ->latest()
            ->get();


        return view('admin.adminDashboard', 
            compact('totalStudents',
                    'totalAdmission',
                    'totalFaculty',
                    'setting',
                    'requests'));
    }
}
