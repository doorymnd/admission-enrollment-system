<?php

namespace App\Http\Controllers\Students\StudentFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Subject;

class IrregularEnrollmentController extends Controller
{
    public function form()
    {
        $studentId = auth('student')->id();
        $school = DB::table('school_settings')->first();

        $enrollment = DB::table('enrollments')
            ->where('student_id', $studentId)
            ->where('status', 'approved')
            ->orderByDesc('id')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'No active enrollment found.');
        }

        // ✅ Check if already submitted
        $hasRequest = DB::table('irregular_requests')
            ->where('student_id', $studentId)
            ->where('semester', $school->semester)
            ->where('school_year', $school->school_year)
            ->exists();

        $grades = DB::table('grades')
            ->where('student_id', $studentId)
            ->where('status', 'released')
            ->get();

        // PASSED SUBJECTS
        $passedSubjects = DB::table('grades')
            ->where('student_id', $studentId)
            ->where('status', 'released')
            ->where('final_grade', '<=', 3.00)
            ->pluck('subject_id')
            ->toArray();

        $subjects = Subject::with('prerequisites')
            ->where('semester', $school->semester)
            ->where('year_level', $enrollment->year_level)
            ->where('course_id', $enrollment->course_id)
            ->whereNotIn('id', $passedSubjects)
            ->get()
            ->map(function ($subject) use ($grades) {

                $subject->can_take = true;
                $subject->prereq_status = null;

                foreach ($subject->prerequisites as $prereq) {

                    $grade = $grades->where('subject_id', $prereq->id)->first();

                    if (!$grade) {
                        $subject->can_take = false;
                        $subject->prereq_status = 'missing';
                        break;
                    }

                    if ($grade->final_grade > 3.00) {
                        $subject->can_take = false;
                        $subject->prereq_status = 'failed';
                        break;
                    }
                }

                return $subject;
            })
            ->values();

        return view('student.enrollment.enrollmentIrreg', compact('subjects', 'hasRequest'));
    }

    public function store(Request $request)
{
    $studentId = auth('student')->id();
    $school = DB::table('school_settings')->first();

    $enrollment = DB::table('enrollments')
        ->where('student_id', $studentId)
        ->where('status', 'approved')
        ->orderByDesc('id')
        ->first();

    if (!$enrollment) {
        return back()->with('error', 'No active enrollment found.');
    }

    // ✅ prevent duplicate pending/approved
    $alreadySubmitted = DB::table('irregular_requests')
        ->where('student_id', $studentId)
        ->where('semester', $school->semester)
        ->where('school_year', $school->school_year)
        ->whereIn('status', ['pending', 'approved'])
        ->exists();

    if ($alreadySubmitted) {
        return back()->with('error', 'You already submitted an irregular request.');
    }

    if (!$request->subjects) {
        return back()->with('error', 'No subjects selected.');
    }

    $validSubjects = Subject::with('prerequisites')
        ->whereIn('id', $request->subjects)
        ->get();

    DB::beginTransaction();

    try {

        foreach ($validSubjects as $subject) {

            DB::table('irregular_requests')->insert([
                'student_id'   => $studentId,
                'subject_id'   => $subject->id,
                'course_id'    => $enrollment->course_id,
                'year_level'   => $enrollment->year_level,
                'semester'     => $school->semester,
                'school_year'  => $school->school_year,
                'status'       => 'pending',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        DB::commit();

        return redirect()->route('student.studentDash')
            ->with('success', 'Irregular request submitted. Waiting for admin approval.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}
}