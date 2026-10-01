<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Enrollment;
use App\Models\Admin\Subject;
use App\Models\Admin\StudentSubject;
use App\Models\Admin\SchoolSetting;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class EnrollmentPromotionController extends Controller
{
    private function getSchoolYear()
    {
        return SchoolSetting::first()->school_year ?? null;
    }

    private function log($action, $description)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
            ]);
        }
    }

    private function assignSubjects($studentId, $courseId, $yearLevel, $semester, $enrollmentId)
    {
        if (!$enrollmentId) {
            return back()->with('error', 'Enrollment ID is missing');
        }

        $schoolYear = SchoolSetting::first()->school_year ?? null;

        $subjects = Subject::where('course_id', $courseId)
            ->where('year_level', $yearLevel)
            ->where('semester', $semester)
            ->get();

        foreach ($subjects as $subject) {
            StudentSubject::updateOrCreate(
                [
                    'student_id'    => $studentId,
                    'subject_id'    => $subject->id,
                    'enrollment_id' => $enrollmentId,
                ],
                [
                    'course_id'   => $courseId,
                    'year_level'  => $yearLevel,
                    'semester'    => $semester,
                    'school_year' => $schoolYear,
                ]
            );
        }
    }

    // =========================
    // VIEW PROMOTE
    // =========================
    public function viewPromote()
    {
        $students = Enrollment::with('student.info')
            ->where('status', 'pending')
            ->get()
            ->groupBy('section');

        $this->log('view_promotion_page', 'Viewed promotion page');

        return view('admin.student.promote', compact('students'));
    }

    // =========================
    // PROMOTE YEAR
    // =========================
    public function promoteYear($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $hasMandatorySummer = Subject::where('course_id', $enrollment->course_id)
            ->where('semester', 3)
            ->exists();

        $hasTakenSummer = Enrollment::where('student_id', $enrollment->student_id)
            ->where('year_level', $enrollment->year_level)
            ->where('semester', 3)
            ->exists();

        if ($hasMandatorySummer && !$hasTakenSummer) {

            $this->log(
                'promotion_failed',
                "Year promotion blocked for student ID {$enrollment->student_id} (missing summer)"
            );

            return back()->with('error', 'Must complete summer first before promotion.');
        }

        $exists = Enrollment::where('student_id', $enrollment->student_id)
            ->where('year_level', $enrollment->year_level + 1)
            ->where('semester', 1)
            ->exists();

        if ($exists) {

            $this->log(
                'promotion_skipped',
                "Student ID {$enrollment->student_id} already promoted"
            );

            return back()->with('error', 'Already promoted.');
        }

        $enrollment->update(['status' => 'completed']);

        $new = Enrollment::create([
            'student_id'  => $enrollment->student_id,
            'course_id'   => $enrollment->course_id,
            'course'      => $enrollment->course,
            'year_level'  => $enrollment->year_level + 1,
            'semester'    => 1,
            'section'     => $this->generateNextSection(
                $enrollment->section,
                $enrollment->year_level + 1
            ),
            'school_year' => $this->getSchoolYear(),
            'status'      => 'approved',
        ]);

        $this->assignSubjects(
            $new->student_id,
            $new->course_id,
            $new->year_level,
            1,
            $new->id
        );

        $this->log(
            'promote_year',
            "Promoted student ID {$new->student_id} to Year {$new->year_level}"
        );

        return back()->with('success', 'Promoted successfully');
    }

    // =========================
    // PROMOTE SEMESTER
    // =========================
    public function promoteSemester($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        if ($enrollment->semester != 1) {

            $this->log(
                'promotion_failed',
                "Invalid semester promotion attempt for student ID {$enrollment->student_id}"
            );

            return back()->with('error', 'Only 1st semester allowed');
        }

        $enrollment->update(['status' => 'completed']);

        $new = Enrollment::create([
            'student_id'  => $enrollment->student_id,
            'course_id'   => $enrollment->course_id,
            'course'      => $enrollment->course,
            'year_level'  => $enrollment->year_level,
            'semester'    => 2,
            'section'     => $enrollment->section,
            'school_year' => $this->getSchoolYear(),
            'status'      => 'approved',
            'type'        => 'regular'
        ]);

        $this->assignSubjects(
            $new->student_id,
            $new->course_id,
            $new->year_level,
            2,
            $new->id
        );

        $this->log(
            'promote_semester',
            "Promoted student ID {$new->student_id} to 2nd semester"
        );

        return back()->with('success', 'Semester promoted');
    }

    // =========================
    // SUMMER
    // =========================
    public function approveSummer($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        if ($enrollment->semester != 2) {

            $this->log(
                'promotion_failed',
                "Invalid summer approval attempt for student ID {$enrollment->student_id}"
            );

            return back()->with('error', 'Invalid');
        }

        $enrollment->update(['status' => 'completed']);

        $new = Enrollment::create([
            'student_id'  => $enrollment->student_id,
            'course_id'   => $enrollment->course_id,
            'course'      => $enrollment->course,
            'year_level'  => $enrollment->year_level,
            'semester'    => 3,
            'section'     => $enrollment->section,
            'school_year' => $enrollment->school_year,
            'status'      => 'approved',
            'type'        => 'regular'
        ]);

        $this->assignSubjects(
            $new->student_id,
            $new->course_id,
            $new->year_level,
            3,
            $new->id
        );

        $this->log(
            'approve_summer',
            "Approved summer for student ID {$new->student_id}"
        );

        return back()->with('success', 'Summer approved');
    }

    private function generateNextSection($oldSection, $nextYearLevel)
    {
        if (preg_match('/^(\d+)([A-Za-z]+)-(\d+)$/', $oldSection, $matches)) {
            return $nextYearLevel . $matches[2] . '-' . (int)$matches[3];
        }

        return $nextYearLevel . '-' . $oldSection;
    }
}