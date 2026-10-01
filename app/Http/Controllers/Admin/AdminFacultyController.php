<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Faculty;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminFacultyController extends Controller
{
    public function viewAdminFaculty()
    {
        $faculties = Faculty::orderBy('last_name')->get();

        // 🔥 LOG: view faculty list
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_faculty_list',
                'description' => 'Viewed faculty list page',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.faculty.adminFacultyPanel', compact('faculties'));
    }

    public function viewFaculty($id)
    {
        $faculty = Faculty::findOrFail($id);

        // ✅ subjects via assignment table
        $subjects = DB::table('faculty_assignments')
            ->join('subjects', 'subjects.id', '=', 'faculty_assignments.subject_id')
            ->join('courses', 'courses.id', '=', 'subjects.course_id')
            ->where('faculty_assignments.faculty_id', $id)
            ->select(
                'subjects.id',
                'courses.code as course',
                'subjects.name',
                'subjects.code',
                'subjects.year_level',
                'faculty_assignments.section',
                'faculty_assignments.semester'
            )
            ->get();

        // 🔥 LOG: view faculty profile
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_faculty_profile',
                'description' => 'Viewed faculty ID: ' . $id,
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.faculty.adminFacultyViewSubjects', compact('faculty', 'subjects'));
    }

    public function toggleStatus($id)
    {
        $faculty = Faculty::findOrFail($id);

        $oldStatus = $faculty->status;

        $faculty->status = $faculty->status === 'active' ? 'inactive' : 'active';
        $faculty->save();

        // 🔥 LOG: status change
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'toggle_faculty_status',
                'description' => 'Changed faculty ID: ' . $id .
                                 ' from ' . $oldStatus . ' to ' . $faculty->status,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Faculty status updated.');
    }
}