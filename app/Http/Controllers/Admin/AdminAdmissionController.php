<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission\Admission;
use App\Models\Admin\Student;
use App\Models\Admin\Course;
use App\Models\Admin\SchoolSetting;
use App\Models\Admin\Subject;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;
use App\Services\SectionService;
use App\Mail\CreateStudentAccountMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\ImportProgress\ImportBatch;
use App\Models\ImportProgress\FailedImportRow;

class AdminAdmissionController extends Controller
{
    private function logAction($action, $description)
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


    public function viewApprovedStudents()
    {
        $this->logAction(
            'view_approved_students_import_page',
            'Opened approved students import page'
        );

        $approvedAdmissions = Admission::where('status', 'approved')
            ->orderBy('passed_at', 'desc')
            ->get();

        return view('admin.exam.uploadApprovedStudents', compact('approvedAdmissions'));
    }

    public function importPassedStudents(Request $request)
    {
        $this->logAction(
            'start_import_passed_students',
            'Started importing passed students Excel file'
        );

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $batch = ImportBatch::create([
            'status' => 'processing',
            'processed' => 0,
            'failed' => 0,
            'total' => 0
        ]);

        Excel::import(new class($batch) implements ToCollection, WithHeadingRow {

            private $batch;

            public function __construct($batch)
            {
                $this->batch = $batch;
            }

            public function headingRow(): int
            {
                return 1;
            }

            public function collection($rows)
            {
                $this->batch->update([
                    'total' => count($rows)
                ]);

                foreach ($rows as $row) {

                        
                    try {

                        $row = collect($row)->mapWithKeys(function ($value, $key) {
                            return [strtolower(trim($key)) => $value];
                        });

                        $email = strtolower(trim($row['email'] ?? ''));

                        if (!$email) throw new \Exception("Email missing");

                        $primaryResult   = strtoupper(trim($row['primary_result'] ?? ''));
                        $secondaryResult = strtoupper(trim($row['secondary_result'] ?? ''));
                        $tertiaryResult  = strtoupper(trim($row['tertiary_result'] ?? ''));

                        // Rule 1: Primary is REQUIRED
                        if (!$primaryResult) {
                            throw new \Exception("Primary result is required");
                        }

                        if (!in_array($primaryResult, ['PASSED', 'FAILED'])) {
                            throw new \Exception("Invalid primary result");
                        }

                        // Rule 2: If primary PASSED → others must be blank
                        if ($primaryResult === 'PASSED') {
                            if ($secondaryResult || $tertiaryResult) {
                                throw new \Exception("Secondary/Tertiary must be blank if Primary is PASSED");
                            }
                        }

                        // Rule 3: If primary FAILED → secondary REQUIRED
                        if ($primaryResult === 'FAILED') {
                            if (!$secondaryResult) {
                                throw new \Exception("Secondary result required when Primary FAILED");
                            }

                            if (!in_array($secondaryResult, ['PASSED', 'FAILED'])) {
                                throw new \Exception("Invalid secondary result");
                            }
                        }

                        // Rule 4: If secondary PASSED → tertiary must be blank
                        if ($secondaryResult === 'PASSED') {
                            if ($tertiaryResult) {
                                throw new \Exception("Tertiary must be blank if Secondary is PASSED");
                            }
                        }

                        // Rule 5: If secondary FAILED → tertiary REQUIRED
                        if ($secondaryResult === 'FAILED') {
                            if (!$tertiaryResult) {
                                throw new \Exception("Tertiary result required when Secondary FAILED");
                            }

                            if (!in_array($tertiaryResult, ['PASSED', 'FAILED'])) {
                                throw new \Exception("Invalid tertiary result");
                            }
                        }

                        $admission = Admission::where('email', $email)->first();
                        if (!$admission) throw new \Exception("Admission not found");

                        $course = null;
                        $passedStage = null;

                        if ($primaryResult === 'PASSED') {
                            $course = Course::where('code', $admission->primary_course)->first();
                            $passedStage = 'primary';
                        } 
                        elseif ($secondaryResult === 'PASSED') {
                            $course = Course::where('code', $admission->secondary_course)->first();
                            $passedStage = 'secondary';
                        } 
                        elseif ($tertiaryResult === 'PASSED') {
                            $course = Course::where('code', $admission->tertiary_course)->first();
                            $passedStage = 'tertiary';
                        } 
                        else {
                            throw new \Exception("Student failed all exams");
                        }

                        if (!$course) {
                            throw new \Exception("Course not found");
                        }


                        $school = SchoolSetting::where('enrollment_status', 'open')->first();
                        if (!$school) throw new \Exception("No active school year");

                        $password = Str::random(10);

                        DB::transaction(function () use ($admission, $email, $course, $school, &$password) {

                            // STUDENT
                            $student = Student::firstOrCreate(
                                ['email' => $email],
                                [
                                    'password' => Hash::make($password),
                                    'status' => 'regular'
                                ]
                            );

                            // INFO
                            $student->info()->updateOrCreate(
                                ['student_id' => $student->id],
                                [
                                    'first_name'  => $admission->first_name,
                                    'middle_name' => $admission->middle_name,
                                    'last_name'   => $admission->last_name,
                                    'suffix'      => $admission->suffix,
                                    'phone'       => $admission->phone,
                                    'street'      => $admission->street,
                                    'barangay'    => $admission->barangay,
                                    'municipality'=> $admission->municipality,
                                    'city'        => $admission->city,
                                    'birthdate'   => $admission->birthdate,
                                    'gender'      => $admission->gender,
                                    'shs_school'  => $admission->shs_school,
                                    'shs_course'  => $admission->shs_course,
                                    'gwa'         => $admission->gwa  
                                ]
                            );

                            // ENROLLMENT (NOW CAPTURED)
                            $enrollment = $student->enrollment()->updateOrCreate(
                                ['student_id' => $student->id],
                                [
                                    'course_id'   => $course->id,
                                    'course'      => $course->code,
                                    'year_level'  => 1,
                                    'section'     => SectionService::generate(
                                        $course->id,
                                        1,
                                        $course->section_code,
                                        $course->max_students_per_section ?? 30,
                                        $course->section_per_year ?? 3
                                    ),
                                    'status'      => 'approved',
                                    'school_year' => $school->school_year,
                                    'semester'    => 1,
                                ]
                            );

                            // SUBJECTS AUTO ENROLL (WITH enrollment_id FIX)
                            $subjects = Subject::where('course_id', $course->id)
                                ->where('year_level', 1)
                                ->where('semester', 1)
                                ->get();

                            foreach ($subjects as $subject) {
                                DB::table('student_subjects')->updateOrInsert(
                                    [
                                        'student_id'    => $student->id,
                                        'subject_id'    => $subject->id,
                                        'enrollment_id' => $enrollment->id,
                                    ],
                                    [
                                        'course_id'   => $course->id,
                                        'year_level'  => 1,
                                        'semester'    => 1,
                                        'school_year' => $school->school_year,
                                        'created_at'  => now(),
                                        'updated_at'  => now(),
                                    ]
                                );
                            }

                            // UPDATE ADMISSION
                            $admission->update([
                                'status' => 'approved',
                                'passed_at' => now()
                            ]);
                        });


                        // EMAIL
                        dispatch(function () use ($email, $admission, $password, $course) {
                        Mail::to($email)->send(
                            new CreateStudentAccountMail(
                                $admission->first_name,
                                $admission->last_name,
                                $email,
                                $password,
                                $course
                            )
                        );
                        });

                        $this->batch->increment('processed');

                    } catch (\Exception $e) {

                        $this->batch->increment('failed');

                        FailedImportRow::create([
                            'import_batch_id' => $this->batch->id,
                            'email' => $row['email'] ?? null,
                            'reason' => $e->getMessage(),
                            'row_data' => json_encode($row)
                        ]);
                    }
                }

                $this->batch->update([
                    'status' => 'completed'
                ]);
            }

        }, $request->file('excel_file'));

        return response()->json([
            'batch_id' => $batch->id
        ]);
    }
}