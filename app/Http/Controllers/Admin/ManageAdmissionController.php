<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission\Admission;
use App\Models\Admin\Course;
use App\Models\Admin\AdminLog;
use Illuminate\Http\Request;
use App\Exports\ExamTemplateExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ManageAdmissionController extends Controller
{
    // =========================
    // VIEW ADMISSIONS
    // =========================
    public function index(Request $request)
    {
        $courses = Course::all();

        $admissions = Admission::where('status', 'pending');

        if ($request->course) {
            $admissions->where('primary_course', $request->course);
        }

        $admissions = $admissions->orderBy('created_at', 'desc')->get();

        // LOG: view admission list
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_pending_admissions',
                'description' => 'Viewed pending admission list',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.admission.viewAdminManageAdmission', compact('admissions', 'courses'));
    }

    // =========================
    // APPROVE APPLICANT
    // =========================
    public function approve($id)
    {
        $admission = Admission::findOrFail($id);

        $admission->update([
            'status' => 'approved'
        ]);

        // LOG: approve applicant
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'approve_admission',
                'description' => 'Approved applicant ID: ' . $admission->id,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Applicant approved for exam.');
    }

    // =========================
    // REJECT APPLICANT
    // =========================
    public function reject($id)
    {
        $admission = Admission::findOrFail($id);

        $admission->update([
            'status' => 'rejected'
        ]);

        // LOG: reject applicant
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'reject_admission',
                'description' => 'Rejected applicant ID: ' . $admission->id,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Applicant rejected.');
    }

    // =========================
    // DOWNLOAD TEMPLATE
    // =========================
    public function downloadAdmissionList()
    {
        // LOG: download excel
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'download_exam_template',
                'description' => 'Downloaded exam template file',
                'ip_address' => request()->ip(),
            ]);
        }

        return Excel::download(new ExamTemplateExport, 'exam_template.xlsx');
    }
}