<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class ExamResultController extends Controller
{
    // Show Excel upload form
    public function viewUploadForm()
    {
        // 🔥 LOG: admin viewed exam upload page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_exam_upload_page',
                'description' => 'Opened exam result upload page',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.exam.upload');
    }
}
