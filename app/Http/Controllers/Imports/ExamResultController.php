<?php

namespace App\Http\Controllers\Imports;

use Illuminate\Http\Request;
use App\Imports\ExamResultsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExamResultController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ExamResultsImport, $request->file('file'));
            return back()->with('success', 'Excel uploaded and emails sent successfully.');
        } catch (\Exception $e) {
            Log::error('Excel upload failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to process Excel: ' . $e->getMessage());
        }
    }
}
