<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FacultyPasswordController extends Controller
{
    public function showFacultyChange() 
    {
        return view('faculty.facultyForceChangePass');
    }

    public function storeFacultyChange(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $faculty = Auth::guard('faculty')->user();

        $faculty->password = Hash::make($request->password);
        $faculty->force_password_change = false;
        $faculty->save();

        return redirect()->route('faculty.facultyDash')
            ->with('success', 'Password updated successfully.');
    }
}
