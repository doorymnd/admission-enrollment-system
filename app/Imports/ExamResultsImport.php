<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission\Admission;
// use App\Imports\ExamResultsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExamResultsImport extends Controller
{
    /**
     * Show the page to upload Excel for passed students.
     */
    public function viewApprovedStudents()
    {
        $approvedAdmissions = Admission::where('status', 'approved')
            ->orderBy('passed_at', 'desc')
            ->get();

        return view('admin.exam.uploadApprovedStudents', compact('approvedAdmissions'));
    }

    /**
     * Handle Excel upload and process passed students.
     */
    public function importPassedStudents(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            // Import Excel using the robust ExamResultsImport class
            Excel::import(new ExamResultsImport, $request->file('excel_file'));

            return redirect()->back()
                ->with('success', 'Passed students imported successfully. Accounts created and emails sent.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            Log::error("Excel validation failed: ".$e->getMessage());
            return redirect()->back()->with('error', 'Excel validation failed. Please check your file.');
        } catch (\Exception $e) {
            Log::error("Excel import failed: ".$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while importing the Excel file.');
        }
    }
}
