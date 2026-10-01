<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\StudentInfo;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentEditProfileRequestController extends Controller
{

    public function viewProfileRequest()
    {
        $requests = StudentInfo::with('student')
            ->whereIn('edit_request_status', ['pending', 'approved', 'rejected'])
            ->latest()
            ->get();

        // 🔥 LOG: view request page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_profile_edit_requests',
                'description' => 'Viewed student profile edit requests',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.student.studentProfilePermission', compact('requests'));
    }

    // =========================
    // APPROVE REQUEST
    // =========================
    public function approve($id)
    {
        $info = StudentInfo::findOrFail($id);
        $info->edit_request_status = 'approved';
        $info->save();

        // 🔥 LOG: approve request
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'approve_profile_edit_request',
                'description' => 'Approved profile edit request for student_info ID: ' . $id,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Request approved.');
    }

    // =========================
    // REJECT REQUEST
    // =========================
    public function reject($id)
    {
        $info = StudentInfo::findOrFail($id);
        $info->edit_request_status = 'none';
        $info->save();

        // 🔥 LOG: reject request
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'reject_profile_edit_request',
                'description' => 'Rejected profile edit request for student_info ID: ' . $id,
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Request rejected.');
    }
}