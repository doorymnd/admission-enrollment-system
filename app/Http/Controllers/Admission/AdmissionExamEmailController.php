<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission\Admission;
use App\Mail\ExamScheduleMail;
use Illuminate\Support\Facades\Mail;

class AdmissionExamEmailController extends Controller
{
    public function sendExamEmail(Request $request)
    {
        $request->validate([
            'course' => 'required',
            'exam_date' => 'required|date',
            'exam_time' => 'required',
        ]);

        $admissions = Admission::where('primary_course', $request->course)->get();
        if ($admissions->isEmpty()) {
            return back()->with('error', 'No students found for this course.');
        }

        foreach ($admissions as $student) {
            Mail::to($student->email)->send(
                new ExamScheduleMail(
                    $student,
                    $request->exam_date,
                    $request->exam_time,
                    $request->course
                )
            );
        }

        return back()->with('success', 'Exam emails sent successfully.');
    }
}