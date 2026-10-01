<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Student;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the students with search and filter.
     */
    public function index(Request $request)
    {
        $query = Student::with(['info','enrollment']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhereHas('info', function($q2) use ($search) {
                      $q2->where('first_name', 'like', "%$search%")
                         ->orWhere('last_name', 'like', "%$search%")
                         ->orWhere('student_no', 'like', "%$search%");
                  });
            });
        }

        // Year filter
        if ($request->filled('year_level')) {
            $year = $request->input('year_level');

            $query->whereHas('enrollment', function($q) use ($year) {
                $q->where('year_level', $year);
            });
        }

        // Section filter
        if ($request->filled('section')) {
            $section = $request->input('section');

            $query->whereHas('enrollment', function($q) use ($section) {
                $q->where('section', 'like', "%$section%");
            });
        }

        $students = $query->get();

        // 🔥 LOG: View Students Page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_students',
                'description' => 'Viewed student list page',
                'ip_address' => request()->ip(),
            ]);
        }

        if ($request->ajax()) {
            return view('admin.student.partials.studentsTable', compact('students'))->render();
        }

        return view('admin.student.manageStudents', compact('students'));
    }

    /**
     * Show student details
     */
    public function show($id)
    {
        $student = Student::with(['info','enrollment'])->findOrFail($id);

        return view('admin.student.show', compact('student'));
    }

    /**
     * Edit student form
     */
    public function edit($id)
    {
        $student = Student::with(['info','enrollment'])->findOrFail($id);

        // 🔥 LOG: Open Edit Page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_edit_student',
                'description' => 'Opened edit page for student ID: ' . $id,
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.student.edit', compact('student'));
    }

    /**
     * Update student
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Update enrollment
        $student->enrollment()->update([
            'year_level' => $request->year_level,
            'section' => $request->section,
            'status' => $request->status,
            'course' => $request->course,
            'school_year' => $request->school_year,
        ]);

        // Update info
        $student->info()->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'street' => $request->street,
            'barangay' => $request->barangay,
            'municipality' => $request->municipality,
            'city' => $request->city,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
        ]);

        // 🔥 LOG: Update Student
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'update_student',
                'description' => 'Updated student ID: ' . $id,
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully');
    }

    /**
     * Delete student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        // 🔥 LOG: Delete Student
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'delete_student',
                'description' => 'Deleted student ID: ' . $id,
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully');
    }
}