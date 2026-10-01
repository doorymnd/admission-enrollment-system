<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class AddStudentSubjectController extends Controller
{
    public function create(Request $request, $studentId)
    {
        $student = DB::table('students')->where('id', $studentId)->first();

        $studentInfo = DB::table('student_infos')
            ->where('student_id', $studentId)
            ->first();

        $latestEnrollment = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.student_id', $studentId)
            ->orderByDesc('enrollments.id')
            ->select('enrollments.*', 'courses.name as course_name')
            ->first();

        if (!$latestEnrollment) {
            return back()->with('error', 'No enrollment found.');
        }

        

        $search = $request->search;

        $yearLabel = [
            1 => 'First Year',
            2 => 'Second Year',
            3 => 'Third Year',
            4 => 'Fourth Year',
        ][$latestEnrollment->year_level] ?? 'N/A';

        $semesterLabel = [
            1 => 'First Semester',
            2 => 'Second Semester',
        ][$latestEnrollment->semester] ?? 'N/A';

        /*
        |------------------------------------------------------------
        | CURRENT ENROLLED SUBJECTS
        |------------------------------------------------------------
        */
        $enrolledSubjects = DB::table('student_subjects')
            ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('courses', 'subjects.course_id', '=', 'courses.id')
            ->where('student_subjects.student_id', $studentId)
            ->where('student_subjects.enrollment_id', $latestEnrollment->id)
            ->where('student_subjects.is_added', 0)
            ->select(
                'subjects.id',
                'subjects.code',
                'subjects.name',
                'subjects.units',
                'student_subjects.status',
                'courses.name as course_name'
            )
            ->get();

        /*
        |------------------------------------------------------------
        | TOTAL UNITS (ALL SUBJECTS IN THIS ENROLLMENT)
        |------------------------------------------------------------
        */
        $totalUnits = DB::table('student_subjects')
            ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
            ->where('student_subjects.student_id', $studentId)
            ->where('student_subjects.enrollment_id', $latestEnrollment->id)
            ->sum('subjects.units');

        /*
        |------------------------------------------------------------
        | AVAILABLE SUBJECTS (CURRICULUM ONLY)
        |------------------------------------------------------------
        */
        $subjects = DB::table('subjects')
            ->leftJoin('courses', 'subjects.course_id', '=', 'courses.id')
            ->where('subjects.course_id', $latestEnrollment->course_id)
            ->where('subjects.semester', $latestEnrollment->semester)
            ->select(
                'subjects.id',
                'subjects.code',
                'subjects.name',
                'subjects.units',
                'subjects.year_level',
                'subjects.semester',
                'courses.name as course_name'
            )
            ->orderBy('subjects.code')
            ->get();

        /*
        |------------------------------------------------------------
        | ALREADY ADDED SUBJECT IDS
        |------------------------------------------------------------
        */
        $addedSubjectIds = DB::table('student_subjects')
            ->where('student_id', $studentId)
            ->where('enrollment_id', $latestEnrollment->id)
            ->pluck('subject_id')
            ->toArray();

        /*
        |------------------------------------------------------------
        | PASSED SUBJECTS
        |------------------------------------------------------------
        */
        $passedSubjectIds = DB::table('student_subjects')
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->pluck('subject_id')
            ->toArray();

        /*
        |------------------------------------------------------------
        | PREREQUISITES
        |------------------------------------------------------------
        */
        $prerequisites = DB::table('subject_prerequisites')
            ->select('subject_id', 'prerequisite_id')
            ->get()
            ->groupBy('subject_id');

        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'open_student_subject_page',
                'description' => 'Opened subject page for student ID: ' . $studentId,
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.enrollment.addStudentSubject', compact(
            'student',
            'studentInfo',
            'latestEnrollment',
            'subjects',
            'enrolledSubjects',
            'addedSubjectIds',
            'passedSubjectIds',
            'prerequisites',
            'search',
            'totalUnits',
            'yearLabel',
            'semesterLabel'
        ));
    }

    /*
    |------------------------------------------------------------
    | STORE SUBJECT (FIXED WITH is_added)
    |------------------------------------------------------------
    */
    public function store(Request $request, $studentId)
    {
        $enrollment = DB::table('enrollments')
            ->where('student_id', $studentId)
            ->orderByDesc('id')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'No enrollment found.');
        }

        $subject = DB::table('subjects')
            ->where('id', $request->subject_id)
            ->first();

        if (!$subject) {
            return back()->with('error', 'Subject not found.');
        }

        /*
        | CHECK DUPLICATE (FIXED)
        */
        $exists = DB::table('student_subjects')
            ->where('student_id', $studentId)
            ->where('subject_id', $request->subject_id)
            ->where('enrollment_id', $enrollment->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Subject already added.');
        }

        /*
        | UNIT LIMIT
        */
        $totalUnits = DB::table('student_subjects')
            ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
            ->where('student_subjects.student_id', $studentId)
            ->where('student_subjects.enrollment_id', $enrollment->id)
            ->sum('subjects.units');

        if (($totalUnits + $subject->units) > 24) {
            return back()->with('error', 'Unit limit reached for this semester.');
        }

        /*
        | PREREQUISITES
        */
        $prereqs = DB::table('subject_prerequisites')
            ->where('subject_id', $subject->id)
            ->pluck('prerequisite_id')
            ->toArray();

        foreach ($prereqs as $prereqId) {
            $completed = DB::table('student_subjects')
                ->where('student_id', $studentId)
                ->where('subject_id', $prereqId)
                ->where('status', 'completed')
                ->exists();

            if (!$completed) {
                return back()->with('error', 'Cannot add subject. Prerequisite not completed.');
            }
        }

        /*
        | INSERT SUBJECT (FIXED)
        */
        DB::table('student_subjects')->insert([
            'student_id'    => $studentId,
            'enrollment_id' => $enrollment->id,
            'subject_id'    => $request->subject_id,
            'course_id'     => $enrollment->course_id,
            'year_level'    => $subject->year_level,
            'semester'      => $subject->semester,
            'school_year'   => $enrollment->school_year,
            'status'        => 'ongoing',

            // 🔥 IMPORTANT FIX
            'is_added'      => 1,

            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'add_student_subject',
                'description' => 'Added subject ID ' . $request->subject_id .
                                 ' to student ID ' . $studentId,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Subject added successfully.');
    }

    /*
    |------------------------------------------------------------
    | REMOVE SUBJECT (FIXED)
    |------------------------------------------------------------
    */
    public function removeSubject($studentId, Request $request)
    {
        $subjectId = $request->subject_id;

        if (!$subjectId) {
            return back()->with('error', 'Subject not found.');
        }

        

        $enrollment = DB::table('enrollments')
            ->where('student_id', $studentId)
            ->orderByDesc('id')
            ->first();

        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'remove_student_subject',
                'description' => 'Removed subject ID ' . $subjectId .
                                 ' from student ID ' . $studentId,
                'ip_address' => request()->ip(),
            ]);
        }


        DB::table('student_subjects')
            ->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('enrollment_id', $enrollment->id)
            ->delete();

        return back()->with('success', 'Subject removed successfully.');
    }
}