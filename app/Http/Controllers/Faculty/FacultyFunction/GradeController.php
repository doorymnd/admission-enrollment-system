<?php

namespace App\Http\Controllers\Faculty\FacultyFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GradesImport;
use App\Models\Admin\Grade;
use App\Models\Admin\Enrollment;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\FacultyAssignment;
use App\Models\Admin\SchoolSetting;
use App\Exports\FacultyUploadedGradesExport;

class GradeController extends Controller
{
    public function showForm()
    {
        $facultyId = auth()->id();

        $schoolSetting = SchoolSetting::first();
        $currentSchoolYear = $schoolSetting ? $schoolSetting->school_year : '';
        $semester = $schoolSetting ? $schoolSetting->semester : 1;

        if ($schoolSetting && $schoolSetting->grade_upload_enabled) {
            session()->flash('grade_status', 'enabled');
        } else {
            session()->flash('grade_status', 'disabled');
        }

        $assignments = FacultyAssignment::with('subject')
            ->where('faculty_id', $facultyId)
            ->where('semester', $semester)
            ->get();

        return view('faculty.grades.upload', compact(
            'assignments',
            'currentSchoolYear'
        ));
    }

    public function upload(Request $request)
    {
        $setting = SchoolSetting::first();

        if (!$setting || !$setting->grade_upload_enabled) {
            return back()->withErrors('Grade uploading is currently disabled by the administrator.');
        }

        $request->validate([
            'assignment_id' => 'required',
            'school_year' => 'required',
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $assignment = FacultyAssignment::where('id', $request->assignment_id)
            ->where('faculty_id', auth()->id())
            ->firstOrFail();

        $existingGrades = Grade::where('assignment_id', $assignment->id)
            ->where('faculty_id', auth()->id())
            ->get();

        if ($existingGrades->isNotEmpty()) {

            $status = $existingGrades->first()->status;

            if (in_array($status, ['submitted', 'approved'])) {
                return back()->withErrors('Grades already submitted and locked.');
            }

            if (in_array($status, ['pending', 'edit_request'])) {
                Grade::where('assignment_id', $assignment->id)
                    ->where('faculty_id', auth()->id())
                    ->delete();
            }
        }

        Excel::import(new GradesImport(
            $assignment->subject_id,
            $assignment->course->code,
            $assignment->section,
            $assignment->year_level,
            $assignment->semester,
            $request->school_year,
            auth()->id(),
            $assignment->id
        ), $request->file('file'));

        return back()->with('success', 'Grades uploaded successfully!');
    }

    public function updateGrades(Request $request)
    {
        if (!$request->grades) {
            return back()->with('error', 'No grades to update.');
        }

        foreach ($request->grades as $id => $data) {

            $grade = Grade::where('id', $id)
                ->where('faculty_id', auth()->id())
                ->first();

            if (!$grade) continue;

            if ($grade->status !== Grade::PENDING) {
                continue;
            }

            $grade->raw_grade = $data['raw_grade'];
            $grade->final_grade = $this->computeFinal($data['raw_grade']);
            $grade->save();
        }

        return back()->with('success', 'Grades updated.');
    }

    // 🔥 FIXED SUBMIT FUNCTION (IMPORTANT)
    public function submitGrades(Request $request)
    {
        $assignmentId = $request->assignment_id;

        Grade::where('faculty_id', auth()->id())
            ->where('assignment_id', $assignmentId)
            ->whereIn('status', [
                Grade::PENDING,
                Grade::EDIT_REQUEST
            ])
            ->update([
                'status' => Grade::SUBMITTED
            ]);

        return back()->with('success', 'Grades submitted successfully!');
    }

    public function requestEditSubject($assignmentId)
    {
        $grades = Grade::where('assignment_id', $assignmentId)
            ->where('faculty_id', auth()->id())
            ->get();

        if ($grades->isEmpty()) {
            return back()->with('error', 'No grades found.');
        }

        foreach ($grades as $grade) {
            if (!in_array($grade->status, [
                Grade::SUBMITTED,
                Grade::APPROVED
            ])) {
                return back()->with('error', 'Edit request not allowed.');
            }
        }

        Grade::where('assignment_id', $assignmentId)
            ->update([
                'status' => Grade::EDIT_REQUEST
            ]);

        return back()->with('success', 'Edit request sent.');
    }

    private function computeFinal($raw)
    {
        if ($raw >= 98) return 1.00;
        elseif ($raw >= 95) return 1.25;
        elseif ($raw >= 92) return 1.50;
        elseif ($raw >= 89) return 1.75;
        elseif ($raw >= 86) return 2.00;
        elseif ($raw >= 83) return 2.25;
        elseif ($raw >= 80) return 2.50;
        elseif ($raw >= 77) return 2.75;
        elseif ($raw >= 75) return 3.00;
        elseif ($raw >= 70) return 4.00;
        else return 5.00;
    }

    public function exportGrades($assignment_id)
    {
        return Excel::download(
            new FacultyUploadedGradesExport($assignment_id),
            'section-grade.xlsx'
        );
    }
}