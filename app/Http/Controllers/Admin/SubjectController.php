<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Subject;
use App\Models\Admin\Course;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Show create subject form
     */
    public function create()
    {
        $courses = Course::orderBy('name')->get();

        // LOG: open create subject page
        $this->log('open_create_subject', 'Opened subject creation page');

        return view('admin.courses.curriculum.createSubject', compact('courses'));
    }

    /**
     * Store subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'code'       => 'required|string',
            'name'       => 'required|string',
            'units'      => 'required|integer|min:1|max:6',
            'year_level' => 'required|integer|min:1|max:4',
            'semester'   => 'required|in:1,2,3',
        ]);

        $subject = Subject::create($validated);

        // LOG: create subject
        $this->log(
            'create_subject',
            'Created subject: ' . $subject->code . ' - ' . $subject->name .
            ' (Course ID: ' . $subject->course_id . ')'
        );

        return redirect()
            ->route('admin.courses.coursesPanel')
            ->with('success', 'Subject added successfully.');
    }

    /**
     * Show all subjects grouped by course
     */
    public function index()
    {
        $courses = Course::with(['subjects' => function ($query) {
            $query->orderBy('year_level')->orderBy('semester');
        }])->get();

        // LOG: view subjects
        $this->log('view_subject_list', 'Viewed all subjects grouped by course');

        return view('admin.courses.coursesSubject', compact('courses'));
    }

    /**
     * Filter by year level
     */
    public function byYear($course_id, $year)
    {
        $course = Course::findOrFail($course_id);

        $subjects = $course->subjects()
            ->where('year_level', $year)
            ->orderBy('semester')
            ->get();

        // LOG: view subjects by year
        $this->log(
            'view_subjects_by_year',
            'Viewed subjects for Course ID: ' . $course_id . ' Year: ' . $year
        );

        return view('admin.courses.coursesbyYear', compact('course', 'year', 'subjects'));
    }

    /**
     * LOG HELPER
     */
    private function log($action, $description)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id'   => $admin->id,
                'action'     => $action,
                'description'=> $description,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}