<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\SchoolSetting;
use App\Models\Admin\AdminLog;

class SemesterSettingsController extends Controller
{
    // =========================
    // VIEW SETTINGS PAGE
    // =========================
    public function viewsemSettings()
    {
        $setting = SchoolSetting::first();

        // LOG VIEW
        $admin = auth('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_semester_settings',
                'description' => 'Opened semester settings page',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.semester.semSettings', compact('setting'));
    }

    // =========================
    // UPDATE SEMESTER + ENROLLMENT + GRADE TOGGLE
    // =========================
    public function updateSemester(Request $request)
    {
        $currentAdmin = auth('admin')->user();

        if ($currentAdmin->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'semester' => 'required|in:1,2,3',
            'enrollment_status' => 'required|in:open,closed',
        ]);

        $setting = SchoolSetting::first();

        $setting->semester = $request->semester;
        $setting->enrollment_status = $request->enrollment_status;
        $setting->grade_upload_enabled = $request->has('grade_upload_enabled');
        $setting->save();

        // LOG UPDATE
        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'update_semester_settings',
            'description' =>
                'Updated semester to ' . $request->semester .
                ', enrollment: ' . $request->enrollment_status .
                ', grade upload: ' . ($request->has('grade_upload_enabled') ? 'enabled' : 'disabled'),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Academic settings updated successfully!');
    }

    // =========================
    // START NEW SCHOOL YEAR
    // =========================
    public function newSchoolYear()
    {
        $currentAdmin = auth('admin')->user();

        if ($currentAdmin->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $setting = SchoolSetting::first();

        list($startYear, $endYear) = explode('-', $setting->school_year);

        $startYear++;
        $endYear++;

        $setting->school_year = $startYear . '-' . $endYear;
        $setting->semester = 1;
        $setting->enrollment_status = 'closed';
        $setting->save();

        // LOG NEW SCHOOL YEAR
        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'start_new_school_year',
            'description' => 'Started new school year: ' . $setting->school_year,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'New school year started successfully!');
    }
}