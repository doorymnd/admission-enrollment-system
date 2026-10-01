<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ClassSchedule;
use App\Models\Admin\Subject;
use App\Models\Admin\Course;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class ClassScheduleController extends Controller
{
    public function adminViewSched(Request $request)
    {
        $query = ClassSchedule::with(['subject', 'faculty']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('subject', function ($q2) use ($search) {
                    $q2->where('code', 'like', "%{$search}%")
                       ->orWhere('name', 'like', "%{$search}%");
                })

                ->orWhereHas('faculty', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                })

                ->orWhere('room', 'like', "%{$search}%")
                ->orWhere('day', 'like', "%{$search}%")
                ->orWhere('start_time', 'like', "%{$search}%")
                ->orWhere('end_time', 'like', "%{$search}%");
            });
        }

        $schedules = $query->get();

        // ✅ LOG VIEW
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_class_schedule',
                'description' => 'Viewed class schedule list',
                'ip_address' => request()->ip(),
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'schedules' => $schedules
            ]);
        }

        return view('admin.schedule.adminViewSchedule', compact('schedules'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $courses = Course::all();

        // ✅ LOG CREATE PAGE VIEW
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'open_create_schedule_page',
                'description' => 'Opened create schedule form',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.schedule.create', compact('subjects', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required',
            'course_id' => 'required',
            'section' => 'required',
            'semester' => 'required',
            'school_year' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        ClassSchedule::create($request->all());

        // ✅ LOG STORE ACTION
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'create_class_schedule',
                'description' => 'Created schedule for subject ID: ' . $request->subject_id,
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->back()->with('success', 'Schedule created successfully');
    }
}