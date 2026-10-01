<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Course;
use App\Models\Admin\Faculty;
use App\Models\Admin\Subject;
use App\Models\Admin\Section;
use App\Models\Admin\FacultyAssignment;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Admin\SchoolSetting;

class CourseAssignmentController extends Controller
{
    public function viewAssignCourse() 
    {
        $departments = Course::select('department')->distinct()->get();
        $faculties = Faculty::all();

        $currentYear = SchoolSetting::first()->current_year;

        if (!$currentYear) {
            $year = date('Y');
            $currentYear = $year . '-' . ($year + 1);
        }

        $startYear = (int) substr($currentYear, 0, 4);

        $years = [];
        for ($i = 0; $i < 5; $i++) {
            $years[] = ($startYear + $i) . '-' . ($startYear + $i + 1);
        }

        // 🔥 LOG: open assign page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_assign_course_page',
                'description' => 'Opened course assignment page',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.faculty.assignCourse', compact('departments','faculties', 'currentYear', 'years'));
    }

    public function getCourses($department)
    {
        $courses = Course::where('department', $department)->get();
        return response()->json($courses);
    }

    public function getYears($course_id)
    {
        $course = Course::findOrFail($course_id);

        $years = [];
        for ($i = 1; $i <= $course->duration_years; $i++) {
            $years[] = $i;
        }

        return response()->json($years);
    }

    public function getSubjects(Request $request)
    {
        $subjects = Subject::where('course_id', $request->course_id)
            ->where('year_level', $request->year_level)
            ->where('semester', $request->semester)
            ->get();

        return response()->json($subjects);
    }

    public function getSections(Request $request)
    {
        $sections = Section::where('course_id', $request->course_id)
            ->where('year_level', $request->year_level)
            ->where('semester', $request->semester)
            ->get();

        return response()->json($sections);
    }

    public function getFaculties(Request $request)
    {
        $request->validate([
            'department' => 'required|string'
        ]);

        $faculties = Faculty::where('department', $request->department)->get();
        return response()->json($faculties);
    }

    public function assignCourse(Request $request)
    {
        $request->validate([
            'department' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'year_level' => 'required|integer',
            'semester' => 'required|integer',
            'section' => 'required|string',
            'faculty' => 'required|array',
            'academic_year' => 'required|string',
        ]);

        $assignedCount = 0;

        foreach ($request->faculty as $subject_id => $faculty_id) {
            if (!$faculty_id) continue;

            FacultyAssignment::updateOrCreate(
                [
                    'course_id' => $request->course_id,
                    'subject_id' => $subject_id,
                    'year_level' => $request->year_level,
                    'semester' => $request->semester,
                    'section' => $request->section,
                    'academic_year' => $request->academic_year,
                ],
                [
                    'faculty_id' => $faculty_id
                ]
            );

            $assignedCount++;
        }

        // 🔥 LOG: assignment action
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'assign_course_subjects',
                'description' => 'Assigned ' . $assignedCount . ' subject(s) to faculty in course ID: ' . $request->course_id,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Subjects assigned successfully!');
    }

    public function viewAssignments(Request $request)
    {
        $query = FacultyAssignment::with(['faculty','course','subject']);

        if ($request->section) {
            $query->where('section', $request->section);
        }

        $assignments = $query->latest()->get();

        $sections = FacultyAssignment::select('section')
            ->distinct()
            ->pluck('section');

        // 🔥 LOG: view assignments page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_faculty_assignments',
                'description' => 'Viewed faculty assignments list',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.faculty.assignSubjectInstructor', compact('assignments', 'sections'));
    }
}