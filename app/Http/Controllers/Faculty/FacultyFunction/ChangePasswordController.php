<?php

namespace App\Http\Controllers\Faculty\FacultyFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function facultyEdit()
    {
        return view('faculty.changePassword');
    }

    public function facultyUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $faculty = Auth::guard('faculty')->user();

        // check current password
        if (!Hash::check($request->current_password, $faculty->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // update password
        $faculty->password = Hash::make($request->new_password);
        $faculty->save();

        return back()->with('success', 'Password changed successfully!');
    }
}
