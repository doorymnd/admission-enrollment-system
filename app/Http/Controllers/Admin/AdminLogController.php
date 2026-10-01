<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminLog;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminLog::with('admin')->orderBy('created_at', 'desc');

        // 🔍 FILTER BY SEARCH (optional)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $logs = AdminLog::with('admin')
    ->when(request('search'), function ($query) {
        $search = request('search');

        $query->where('action', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('admin', function ($q) use ($search) {
                  $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
              });
    })
    ->latest()
    ->get();

        return view('admin.logs.index', compact('logs'));
    }
} 