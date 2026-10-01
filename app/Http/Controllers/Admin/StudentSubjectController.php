<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\StudentInfo;
use App\Models\Admin\Subject;
use App\Models\Admin\Course;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentSubjectController extends Controller
{
    /**
     * Show subject assignment page
     */
    public function create(Request $request)
    {
        $query = $request->input('search');

        $students = StudentInfo::when($query, function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('id', $query);
            })
            ->orderBy('last_name')
            ->paginate(10);

        // 🔥 LOG: view page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_add_subjects_page',
                'description' => 'Viewed student subject assignment page',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.enrollment.adminAddSubjectsEnrollment', [
            'students' => $students,
            'subjects' => Subject::all(),
            'courses'  => Course::all(),
            'search'   => $query,
        ]);
    }

    /**
     * Store assigned subjects
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'   => 'required',
            'subject_ids'  => 'required|array',
            'semester'     => 'required',
            'school_year'  => 'required',
            'year_level'   => 'required',
        ]);

        foreach ($request->subject_ids as $subject_id) {

            DB::table('student_subjects')->updateOrInsert(
                [
                    'student_id'  => $request->student_id,
                    'subject_id'  => $subject_id,
                    'semester'    => $request->semester,
                    'school_year' => $request->school_year,
                ],
                [
                    'course_id'   => $request->course_id,
                    'year_level'  => $request->year_level,
                    'status'      => 'pending',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }

        // 🔥 LOG: assign subjects
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {

            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'assign_student_subjects',
                'description' => 'Assigned ' . count($request->subject_ids) .
                                 ' subjects to student ID: ' . $request->student_id,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Subjects added successfully!');
    }
}