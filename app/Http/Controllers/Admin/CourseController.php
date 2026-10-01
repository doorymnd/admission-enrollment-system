<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Course;
use App\Models\Admin\Program;
use App\Models\Admin\Subject;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display courses with filtering
     */
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $courses = $query->orderBy('code')->get();

        // LOG: view courses
        $this->log('view_courses', 'Viewed courses panel');

        return view('admin.courses.coursesPanel', compact('courses'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $programs = Program::orderBy('name')->get();
        $subjects = Subject::all();

        // LOG: open create page
        $this->log('open_create_course', 'Opened create course page');

        return view('admin.courses.coursesCreate', compact('programs', 'subjects'));
    }

    /**
     * Store new course
     */
    public function store(Request $request)
    {
        $request->validate([
            'department'     => 'required|string',
            'code'           => 'required|string|max:10',
            'name'           => 'required|string|max:255',
            'duration_years' => 'required|integer|min:1',
            'section_code'   => 'required|string',
        ]);

        DB::transaction(function () use ($request) {

            $program = Program::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
            ]);

            Course::create([
                'program_id'     => $program->id,
                'department'     => $request->department,
                'code'           => strtoupper($request->code),
                'name'           => $request->name,
                'duration_years' => $request->duration_years,
                'section_code'   => $request->section_code,
            ]);
        });

        // LOG: create course
        $this->log(
            'create_course',
            'Created course: ' . $request->code . ' - ' . $request->name
        );

        return redirect()
            ->route('admin.courses.coursesPanel')
            ->with('success', 'Program and Course saved successfully!');
    }

    /**
     * Edit form
     */
    public function edit(Course $course)
    {
        $this->log('open_edit_course', 'Opened edit course ID: ' . $course->id);

        return view('admin.courses.coursesEdit', compact('course'));
    }

    /**
     * Update course
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'duration_years' => 'nullable|integer',
        ]);

        DB::transaction(function () use ($request, $course) {

            $course->update([
                'code' => $request->code,
                'name' => $request->name,
                'duration_years' => $request->duration_years,
            ]);

            if ($course->program) {
                $course->program->update([
                    'code' => $request->code,
                    'name' => $request->name,
                ]);
            }
        });

        // LOG: update course
        $this->log(
            'update_course',
            'Updated course ID: ' . $course->id
        );

        return redirect()
            ->route('admin.courses.coursesPanel')
            ->with('success', 'Course and Program updated successfully.');
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        $courseId = $course->id;
        $courseName = $course->name;

        $course->delete();

        // LOG: delete course
        $this->log(
            'delete_course',
            'Deleted course: ' . $courseName . ' (ID: ' . $courseId . ')'
        );

        return redirect()
            ->route('admin.courses.coursesPanel')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * AJAX filter
     */
    public function filterCourses(Request $request)
    {
        $yearLevel = $request->year_level;
        $semester  = $request->semester;

        $courses = Course::where('year_level', $yearLevel)
            ->where('semester', $semester)
            ->get(['id','code','name']);

        return response()->json($courses);
    }

    /**
     * LOG HELPER (IMPORTANT)
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