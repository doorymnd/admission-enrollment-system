<?php

namespace App\Http\Controllers\Admin\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Grade;
use App\Models\Admin\Enrollment;
use App\Models\Admin\Course;
use App\Models\Admin\Faculty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\StudentService;
use Illuminate\Support\Facades\Hash;
use App\Mail\FacultyAccountCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DeanFunctionController extends Controller
{
    public function deanGrades(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $department = $admin->department;

        $status = $request->status;
        $sectionSearch = $request->section;
        $year = $request->year;
        $instructor = $request->instructor;

        $grades = Grade::with('student.info','subject','faculty')
            ->whereHas('subject.course', function($q) use ($department){
                $q->where('department', $department);
            })

            ->whereIn('status', ['submitted', 'approved', 'released', 'edit_request'])

            ->when($status, function($query) use ($status){
                $query->where('status', $status);
            })

            // SECTION FILTER
            ->when($sectionSearch, function($query) use ($sectionSearch){
                $query->where('section', 'LIKE', '%' . $sectionSearch . '%');
            })

            // YEAR FILTER
            ->when($year, function($query) use ($year){
                $query->where('year_level', $year); // adjust if column name is different
            })

            // INSTRUCTOR FILTER
            ->when($instructor, function($query) use ($instructor){
                $query->whereHas('faculty', function($q) use ($instructor){
                    $q->where('first_name', 'LIKE', "%$instructor%")
                    ->orWhere('last_name', 'LIKE', "%$instructor%");
                });
            })

            ->orderBy('section')
            ->orderBy('semester')
            ->get();

        $grouped = $grades
            ->groupBy(function($item){
                return $item->section . '-' . $item->semester;
            })
            ->map(function($sectionGroup){
                return $sectionGroup
                    ->groupBy('subject_id')
                    ->map(function($subjectGroup){
                        return $subjectGroup; 
                    });
            });

        return view('admin.dean.gradesApproval', [
            'grades' => $grouped,
            'status' => $status
        ]);
    }

    public function deanApproveSubject($section, $semester, $subject)
    {
        $department = Auth::guard('admin')->user()->department;

        Grade::where('section', $section)
            ->where('semester', $semester)
            ->where('subject_id', $subject)
            ->whereHas('subject.course', function($q) use ($department){
                $q->where('department', $department);
            })
            ->update(['status' => 'approved']);

        return back()->with('success', 'Subject grades approved!');
    }

public function deanReturnSubject(Request $request, $section, $semester, $subject)
{
    $request->validate([
        'note' => 'required|string|max:500'
    ]);

    $department = Auth::guard('admin')->user()->department;

    Grade::where('section', $section)
        ->where('semester', $semester)
        ->where('subject_id', $subject)
        ->whereHas('subject.course', function($q) use ($department){
            $q->where('department', $department);
        })
        ->update([
            'status' => 'edit_request',
            'dean_note' => $request->note
        ]);

    return back()->with('success', 'Grades returned to faculty with note!');
}


public function deanReleaseSubject($section, $semester, $subject)
{
    $department = Auth::guard('admin')->user()->department;

    DB::beginTransaction();

    try {

        $pending = Grade::where('section',$section)
            ->where('semester',$semester)
            ->where('subject_id',$subject)
            ->whereHas('subject.course', function($q) use ($department){
                $q->where('department', $department);
            })
            ->where('status','pending')
            ->exists();

        if ($pending) {
            return back()->with('error','Approve subject first!');
        }

        Grade::where('section', $section)
            ->where('semester', $semester)
            ->where('subject_id', $subject)
            ->whereHas('subject.course', function($q) use ($department){
                $q->where('department', $department);
            })
            ->update(['status' => 'released']);

        $studentIds = Grade::where('section', $section)
            ->where('semester', $semester)
            ->where('subject_id', $subject)
            ->pluck('student_id')
            ->unique();

        foreach ($studentIds as $studentId) {

            $hasFailed = Grade::where('student_id', $studentId)
                ->where('section', $section)
                ->where('semester', $semester)
                ->where('status', 'released')
                ->where('final_grade', 5.00)
                ->exists();

            $enrollment = Enrollment::where('student_id', $studentId)
                ->latest('id')
                ->first();

            if ($enrollment) {
                $enrollment->update([
                    'type' => $hasFailed ? 'irregular' : 'regular'
                ]);
            }
        }

        DB::commit();

        return back()->with('success', 'Subject grades released and enrollment updated!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong!');
    }
}


    public function deanUnlockSubject($section, $semester, $subject)
{
    $department = Auth::guard('admin')->user()->department;

    Grade::where('section', $section)
        ->where('semester', $semester)
        ->where('subject_id', $subject)
        ->whereHas('subject.course', function($q) use ($department){
            $q->where('department', $department);
        })
        ->update(['status' => 'pending']);

    return back()->with('success', 'Grades unlocked for editing!');
}




// ADD INSTRUCTOR

    public function viewDeanFaculty()
    {
        $admin = Auth::guard('admin')->user();
        $department = $admin->department;

        $faculties = Faculty::where('department', $department)
            ->orderBy('last_name')
            ->get();

        return view('admin.dean.deanFaculty', compact('faculties'));
    }

    public function deanToggleStatus($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->status = $faculty->status === 'active' ? 'inactive' : 'active';
        $faculty->save();

        return back();
    }



    public function deanCreateAdminFaculty()
    {

    $admin = Auth::guard('admin')->user();
        $department = Course::select('department')->distinct()->get();

        

        return view('admin.dean.addInstructor', compact('department', 'admin'));
    }



        public function deanStoreAdminFaculty(Request $request)
{
    $request->validate([
        'first_name' => 'required',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required',
        'suffix' => 'nullable|string|max:10',
        'email' => 'required|email|unique:faculties',
        'role' => 'required',
    ]);

    $admin = Auth::guard('admin')->user(); // ✅ get dean

    $temporaryPassword = 'Temp@123';

    $faculty = Faculty::create([
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
        'suffix' => $request->suffix,
        'email' => $request->email,
        'role' => $request->role,

        // ✅ FORCE SAME DEPARTMENT
        'department' => $admin->department,

        'password' => Hash::make($temporaryPassword),
        'force_password_change' => true,
    ]);

    Mail::to($faculty->email)->send(new FacultyAccountCreated($faculty, $temporaryPassword));

    return redirect()->back()->with('success', 'Faculty account created successfully');
}
    
}
