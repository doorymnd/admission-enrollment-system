<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OldStudentsImport;
use App\Exports\OldStudentExport;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Auth;

class OldStudentController extends Controller
{

    /**
     * View import page
     */
    public function viewOldStudents()
    {
        // 🔥 LOG: view import page
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_old_students_import',
                'description' => 'Opened old student import page',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.student.importOldStudents.importOldStudents');
    }

    /**
     * Import old students
     */
    public function importOldStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240'
        ]);

        $import = new OldStudentsImport();
        Excel::import($import, $request->file('file'));

        // 🔥 LOG: import action
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'import_old_students',
                'description' => 'Imported old students. Processed: ' . $import->processedRows . ' / Total: ' . $import->totalRows,
                'ip_address' => request()->ip(),
            ]);
        }

        return response()->json([
            'success' => true,
            'total' => $import->totalRows,
            'processed' => $import->processedRows
        ]);
    }

    /**
     * Download Excel template
     */
    public function downloadOldStudent()
    {
        // 🔥 LOG: download template
        $admin = Auth::guard('admin')->user();

        if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'download_old_students_template',
                'description' => 'Downloaded old student Excel template',
                'ip_address' => request()->ip(),
            ]);
        }

        return Excel::download(new OldStudentExport, 'old_student_header.xlsx');
    }
}