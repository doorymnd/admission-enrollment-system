<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Subject;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class SubjectPrerequisiteController extends Controller
{

    public function prerequisitesEdit($id)
    {
        $subject = Subject::findOrFail($id);

        $subjects = Subject::where('course_id', $subject->course_id)
            ->where('id', '!=', $subject->id)
            ->get()
            ->groupBy(function ($item) {

                $yearMap = [
                    1 => 'First Year',
                    2 => 'Second Year',
                    3 => 'Third Year',
                    4 => 'Fourth Year',
                ];

                $semesterMap = [
                    1 => 'First Semester',
                    2 => 'Second Semester',
                    3 => 'Summer',
                ];

                $year = $yearMap[$item->year_level] ?? ($item->year_level . ' Year');
                $semester = $semesterMap[$item->semester] ?? ('Semester ' . $item->semester);

                return $year . ' - ' . $semester;
            });

        $selected = $subject->prerequisites()
            ->pluck('prerequisite_id')
            ->toArray();

        // =========================
        // 🔥 ADMIN LOG (VIEW PAGE)
        // =========================
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_prerequisite_page',
                'description' => 'Opened prerequisites page for subject ID: ' . $subject->id,
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.subjects.managePrerequisites', compact(
            'subject',
            'subjects',
            'selected'
        ));
    }


    public function prerequisitesUpdate(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $prerequisites = $request->input('prerequisites', []);

        $prerequisites = array_filter($prerequisites, function ($pid) use ($subject) {
            return $pid != $subject->id;
        });

        $subject->prerequisites()->sync($prerequisites);

        // =========================
        // 🔥 ADMIN LOG (UPDATE)
        // =========================
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'update_prerequisites',
                'description' => 'Updated prerequisites for subject ID: ' . $subject->id .
                                 ' | Count: ' . count($prerequisites),
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Prerequisites updated successfully!');
    }
}