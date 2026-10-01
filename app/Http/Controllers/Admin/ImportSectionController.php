<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\SectionsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportSectionController extends Controller
{
    public function showImportForm()
    {
        return view('admin.section.uploadSection');
    }

    public function import(Request $request)
    {
        try {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new SectionsImport, $request->file('excel_file'));

        return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}