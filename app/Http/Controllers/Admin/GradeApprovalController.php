<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Grade;
use App\Models\Admin\AdminLog;

class GradeApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $grades = Grade::with('student.info','subject')
            ->whereIn('status', ['submitted', 'approved', 'released', 'edit_request'])
            ->when($status, function($query) use ($status){
                $query->where('status', $status);
            })
            ->orderBy('section')
            ->orderBy('semester')
            ->get();

        // GROUP SAFELY
        $grouped = $grades
            ->groupBy(function($item){
                return $item->section . '|SEM|' . $item->semester;
            })
            ->map(function($sectionGroup){
                return $sectionGroup
                    ->groupBy('subject_id')
                    ->map(function($subjectGroup){
                        return collect($subjectGroup);
                    });
            });

        // =========================
        // ADMIN LOG (VIEW PAGE)
        // =========================
        $admin = auth('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_grade_approval_page',
                'description' => 'Viewed grade approval page' .
                                 ($status ? ' filtered by status: ' . $status : ''),
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.grades.pending', [
            'grades' => $grouped,
            'status' => $status
        ]);
    }
}