<?php

namespace App\Imports;

use App\Models\Admin\Student;
use App\Models\Admin\StudentInfo;
use App\Models\Admin\Enrollment;
use App\Models\Admin\Course;
use App\Models\Admin\Subject;
use App\Models\Admin\StudentSubject;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\Mail\OldStudentAccountMail;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class OldStudentsImport implements ToModel, WithHeadingRow, WithChunkReading, WithEvents
{
    public $totalRows = 0;
    public $processedRows = 0;

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $totalRows = $event->getReader()->getTotalRows();
                $sheetTotal = array_values($totalRows)[0] ?? 0;

                $this->totalRows = max($sheetTotal - 1, 0);
            },
        ];
    }

    public function model(array $row)
    {
        $this->processedRows++;

        // 🔐 Generate password
        $password = Str::random(10);

        // ✅ STUDENT (NO DUPLICATES)
        $student = Student::updateOrCreate(
            ['student_no' => $row['student_no']],
            [
                'email'    => $row['email'],
                'password' => Hash::make($password),
            ]
        );

        // ✅ STUDENT INFO (UPDATE NOT DUPLICATE)
        StudentInfo::updateOrCreate(
            ['student_id' => $student->id],
            [
                'first_name'   => $row['first_name'],
                'middle_name'  => $row['middle_name'] ?? null,
                'last_name'    => $row['last_name'],
                'suffix'       => $row['suffix'] ?? null,
                'phone'        => $row['phone'] ?? null,
                'street'       => $row['street'] ?? null,
                'barangay'     => $row['barangay'] ?? null,
                'municipality' => $row['municipality'] ?? null,
                'city'         => $row['city'] ?? null,
                'birth_date'   => $row['birth_date'] ?? null,
                'gender'       => $row['gender'] ?? null,
            ]
        );

        // ✅ FIND COURSE
        $course = Course::where('code', $row['course'])->first();

        if (!$course) {
            return null;
        }

        // ✅ ENROLLMENT (NO DUPLICATES PER STUDENT+SEMESTER+YEAR+SY)
        $enrollment = Enrollment::updateOrCreate(
            [
                'student_id'  => $student->id,
                'course_id'   => $course->id,
                'year_level'  => $row['year_level'],
                'semester'    => $row['semester'],
                'school_year' => $row['school_year'],
            ],
            [
                'course'  => $course->code,
                'section' => $row['section'],
                'status'  => $row['status'],
            ]
        );

        // ✅ GET SUBJECTS (BASED ON COURSE + YEAR + SEMESTER)
        $subjects = Subject::where('course_id', $course->id)
            ->where('year_level', trim($row['year_level']))
            ->where('semester', trim($row['semester']))
            ->get();

        if ($subjects->isEmpty()) {
            return null;
        }

        // ✅ STUDENT SUBJECTS (NO DUPLICATES)
        foreach ($subjects as $subject) {
            StudentSubject::updateOrCreate(
                [
                    'student_id'   => $student->id,
                    'subject_id'   => $subject->id,
                ],
                [
                    'year_level'        => $row['year_level'],
                    'semester'          => $row['semester'],
                    'school_year'       => $row['school_year'],
                    'course_id'     => $enrollment->course_id,
                ]
            );
        }

        // 📧 SEND EMAIL
        Mail::to($student->email)
            ->queue(new OldStudentAccountMail($student, $password));
    }

    public function chunkSize(): int
    {
        return 100;
    }
}