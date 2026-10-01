<?php

namespace App\Http\Controllers\Students\StudentFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        return view('student.viewStudentFunction.changePassword');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $student = Auth::guard('student')->user();

        // check current password
        if (!Hash::check($request->current_password, $student->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // update password
        $student->password = Hash::make($request->new_password);
        $student->save();

        return back()->with('success', 'Password changed successfully!');
    }
}