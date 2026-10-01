<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class IrregularApprovalController extends Controller
{
    public function pending()
    {
        $requests = DB::table('irregular_requests')
            ->join('students', 'irregular_requests.student_id', '=', 'students.id')
            ->join('student_infos', 'irregular_requests.student_id', '=', 'student_infos.student_id')
            ->join('subjects', 'irregular_requests.subject_id', '=', 'subjects.id')
            ->where('irregular_requests.status', 'pending')
            ->select(
                'irregular_requests.*',
                'students.student_no',
                'student_infos.first_name',
                'student_infos.last_name',
                'subjects.name as subject_name'
            )
            ->get();

    

        return view('admin.student.irregular.enrollmentApprovalPendingSubjects', compact('requests'));
    }

    // =========================
    // APPROVE SINGLE SUBJECT
    // =========================
    public function approve($id)
    {
        $request = DB::table('irregular_requests')->where('id', $id)->first();

        if (!$request) {
            return back()->with('error', 'Request not found.');
        }

        if ($request->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        DB::beginTransaction();

        try {

            $studentId = $request->student_id;
            $school = DB::table('school_settings')->first();

            $currentEnrollment = DB::table('enrollments')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->where('enrollments.student_id', $studentId)
                ->whereIn('enrollments.status', ['approved', 'pending'])
                ->orderByDesc('enrollments.id')
                ->select('enrollments.*', 'courses.code as course_code')
                ->first();

            if (!$currentEnrollment) {
                return back()->with('error', 'No active enrollment.');
            }

            $enrollment = DB::table('enrollments')
                ->where('student_id', $studentId)
                ->where('type', 'irregular')
                ->where('semester', $school->semester)
                ->where('school_year', $school->school_year)
                ->first();

            if (!$enrollment) {
                $enrollmentId = DB::table('enrollments')->insertGetId([
                    'student_id'  => $studentId,
                    'course_id'   => $currentEnrollment->course_id,
                    'course'      => $currentEnrollment->course_code,
                    'section'     => $currentEnrollment->section,
                    'year_level'  => $currentEnrollment->year_level,
                    'semester'    => $school->semester,
                    'school_year' => $school->school_year,
                    'status'      => 'approved',
                    'type'        => 'irregular',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            } else {
                $enrollmentId = $enrollment->id;
            }

            DB::table('student_subjects')->updateOrInsert(
                [
                    'student_id'  => $studentId,
                    'subject_id'  => $request->subject_id,
                    'semester'    => $school->semester,
                    'school_year' => $school->school_year,
                ],
                [
                    'enrollment_id' => $enrollmentId,
                    'course_id'     => $currentEnrollment->course_id,
                    'year_level'    => $currentEnrollment->year_level,
                    'updated_at'    => now(),
                    'created_at'    => now(),
                ]
            );

            DB::table('irregular_requests')
                ->where('id', $id)
                ->update([
                    'status' => 'approved',
                    'updated_at' => now()
                ]);

            // 🔥 LOG SINGLE APPROVE
            $admin = Auth::guard('admin')->user();

            if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
                AdminLog::create([
                    'admin_id' => $admin->id,
                    'action' => 'approve_irregular_subject',
                    'description' => 'Approved subject ID ' . $request->subject_id .
                                     ' for student ID ' . $studentId,
                    'ip_address' => request()->ip(),
                ]);
            }

            DB::commit();

            return back()->with('success', 'Approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // =========================
    // APPROVE ALL SUBJECTS
    // =========================
    public function approveAll($studentId)
    {
        $requests = DB::table('irregular_requests')
            ->where('student_id', $studentId)
            ->where('status', 'pending')
            ->get();

        if ($requests->isEmpty()) {
            return back()->with('error', 'No pending requests.');
        }

        DB::beginTransaction();

        try {

            $school = DB::table('school_settings')->first();

            $currentEnrollment = DB::table('enrollments')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->where('enrollments.student_id', $studentId)
                ->whereIn('enrollments.status', ['approved', 'pending'])
                ->orderByDesc('enrollments.id')
                ->select('enrollments.*', 'courses.code as course_code')
                ->first();

            if (!$currentEnrollment) {
                return back()->with('error', 'No active enrollment found.');
            }

            $enrollment = DB::table('enrollments')
                ->where('student_id', $studentId)
                ->where('type', 'irregular')
                ->where('semester', $school->semester)
                ->where('school_year', $school->school_year)
                ->first();

            if (!$enrollment) {
                $enrollmentId = DB::table('enrollments')->insertGetId([
                    'student_id'  => $studentId,
                    'course_id'   => $currentEnrollment->course_id,
                    'course'      => $currentEnrollment->course_code,
                    'section'     => $currentEnrollment->section,
                    'year_level'  => $currentEnrollment->year_level,
                    'semester'    => $school->semester,
                    'school_year' => $school->school_year,
                    'status'      => 'approved',
                    'type'        => 'irregular',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            } else {
                $enrollmentId = $enrollment->id;
            }

            foreach ($requests as $req) {

                DB::table('student_subjects')->updateOrInsert(
                    [
                        'student_id'  => $studentId,
                        'subject_id'  => $req->subject_id,
                        'semester'    => $req->semester,
                        'school_year' => $req->school_year,
                    ],
                    [
                        'enrollment_id' => $enrollmentId,
                        'course_id'     => $currentEnrollment->course_id,
                        'year_level'    => $req->year_level,
                        'updated_at'    => now(),
                        'created_at'    => now(),
                    ]
                );

                DB::table('irregular_requests')
                    ->where('id', $req->id)
                    ->update([
                        'status' => 'approved',
                        'updated_at' => now()
                    ]);
            }

            // 🔥 LOG BULK APPROVE
            $admin = Auth::guard('admin')->user();

            if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
                AdminLog::create([
                    'admin_id' => $admin->id,
                    'action' => 'approve_all_irregular_subjects',
                    'description' => 'Approved ' . count($requests) .
                                     ' irregular subjects for student ID ' . $studentId,
                    'ip_address' => request()->ip(),
                ]);
            }

            DB::commit();

            return back()->with('success', 'All subjects approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}