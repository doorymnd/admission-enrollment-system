<?php

namespace App\Http\Controllers\Admin\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ClassSchedule;
use App\Models\Admin\Subject;
use App\Models\Admin\Faculty;
use App\Models\Admin\StudentSubject;
use App\Models\Admin\Course;
use App\Models\Admin\Section;
use App\Models\Admin\SchoolSetting;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class DeanSchedulingController extends Controller
{
    public function viewSched(Request $request)
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

        // 🔥 LOG: view schedules
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin', 'dean'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_class_schedules',
                'description' => 'Viewed class schedule list (Dean panel)',
                'ip_address' => request()->ip(),
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'schedules' => $schedules
            ]);
        }

        return view('admin.dean.schedule.deanViewSchedule', compact('schedules'));
    }

    public function create()
    {
        $dean = auth('admin')->user();

        if (!$dean) {
            return redirect()->route('login');
        }

        $schoolYear = SchoolSetting::first();

        $courseIds = Course::where('department', $dean->department)->pluck('id');

        $subjects = Subject::whereIn('course_id', $courseIds)->get();
        $courses = Course::where('department', $dean->department)->get();
        $faculties = Faculty::all();

        // 🔥 LOG: open schedule form
        if (in_array(strtolower($dean->role), ['admin', 'super_admin', 'dean'])) {
            AdminLog::create([
                'admin_id' => $dean->id,
                'action' => 'open_schedule_form',
                'description' => 'Opened class scheduling form',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.dean.schedule.deanScheduling', compact(
            'subjects',
            'courses',
            'schoolYear',
            'faculties'
        ));
    }

    public function store(Request $request)
{
    $dean = auth('admin')->user();

    if (!$dean) {
        return redirect()->route('login');
    }

    $request->validate([
        'subject_id'   => 'required|exists:subjects,id',
        'course_id'    => 'required|exists:courses,id',
        'section_id'   => 'required|exists:sections,id',
        'semester'     => 'required',
        'school_year'  => 'required',
        'day'          => 'required',
        'start_time'   => 'required',
        'end_time'     => 'required',
    ]);

    // ✅ Validate course
    $course = Course::where('id', $request->course_id)
        ->where('department', $dean->department)
        ->first();

    if (!$course) {
        return back()->with('error', 'Invalid course for your department.');
    }

    // ✅ Validate subject
    $subject = Subject::where('id', $request->subject_id)
        ->where('course_id', $course->id)
        ->first();

    if (!$subject) {
        return back()->with('error', 'Invalid subject for selected course.');
    }

    // ✅ Validate section
    $section = Section::where('id', $request->section_id)
        ->where('course_id', $course->id)
        ->first();

    if (!$section) {
        return back()->with('error', 'Invalid section for selected course.');
    }

    $semester = $this->convertSemester($request->semester);
    $sectionName = $section->section;

    // 🔥 COMMON TIME CONFLICT LOGIC
    $conflictQuery = function ($query) use ($request, $semester) {
        $query->where('day', $request->day)
              ->where('semester', $semester)
              ->where('school_year', $request->school_year)
              ->where(function ($q) use ($request) {
                  $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_time', '<=', $request->start_time)
                           ->where('end_time', '>=', $request->end_time);
                    });
              });
    };

    // 🚫 1. INSTRUCTOR CONFLICT
    $instructorConflict = ClassSchedule::where('faculty_id', $request->faculty_id)
        ->where($conflictQuery)
        ->exists();

    if ($instructorConflict) {
        return back()->with('error', 'Instructor already has a schedule at that time.');
    }

    // 🚫 2. ROOM CONFLICT
    if ($request->room) {
        $roomConflict = ClassSchedule::where('room', $request->room)
            ->where($conflictQuery)
            ->exists();

        if ($roomConflict) {
            return back()->with('error', 'Room is already occupied at that time.');
        }
    }

    // 🚫 3. SECTION CONFLICT
    $sectionConflict = ClassSchedule::where('section', $sectionName)
        ->where($conflictQuery)
        ->exists();

    if ($sectionConflict) {
        return back()->with('error', 'This section already has a schedule at that time.');
    }

    // ✅ SAVE SCHEDULE
    ClassSchedule::updateOrCreate(
        [
            'subject_id'  => $subject->id,
            'course_id'   => $course->id,
            'section'     => $sectionName,
            'semester'    => $semester,
            'school_year' => $request->school_year,
            'day'         => $request->day,
        ],
        [
            'faculty_id'  => $request->faculty_id,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'room'        => $request->room,
        ]
    );

    // 🔥 LOG
    if (in_array(strtolower($dean->role), ['admin', 'super_admin', 'dean'])) {
        AdminLog::create([
            'admin_id' => $dean->id,
            'action' => 'create_class_schedule',
            'description' =>
                'Created/Updated schedule: Subject ID ' . $subject->id .
                ' | Course ID ' . $course->id .
                ' | Section ' . $sectionName .
                ' | Day ' . $request->day,
            'ip_address' => request()->ip(),
        ]);
    }

    return back()->with('success', 'Schedule saved successfully.');
}

    public function getSubjects($courseId)
    {
        $dean = auth('admin')->user();

        if (!$dean) {
            return response()->json([], 403);
        }

        $course = Course::where('id', $courseId)
            ->where('department', $dean->department)
            ->first();

        if (!$course) {
            return response()->json([], 403);
        }

        return response()->json(
            Subject::where('course_id', $course->id)->get()
        );
    }

    public function getSections($courseId)
    {
        $dean = auth('admin')->user();

        if (!$dean) {
            return response()->json([], 403);
        }

        $course = Course::where('id', $courseId)
            ->where('department', $dean->department)
            ->first();

        if (!$course) {
            return response()->json([], 403);
        }

        return response()->json(
            Section::where('course_id', $course->id)->get()
        );
    }

    private function convertSemester($semester)
    {
        switch (strtolower($semester)) {
            case 'first semester':
            case '1st semester':
            case '1':
                return 1;

            case 'second semester':
            case '2nd semester':
            case '2':
                return 2;

            case 'summer':
            case '3':
                return 3;

            default:
                return null;
        }
    }
}