<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentForceChangeController extends Controller
{


    public function showChangePasswordForm()
    {
        return view('student.studentForceChangePass');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student = Auth::guard('student')->user();

        $student->password = Hash::make($request->password);
        $student->force_change_student_password = false; 
        $student->save();

        return redirect()->route('student.studentDash')->with('success', 'Password updated successfully!');
    }
}
