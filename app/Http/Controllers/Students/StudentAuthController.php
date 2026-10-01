<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{

    public function viewStudentLogin(Request $request)
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.studentDash');
        }
        return view('student.studentLogin');
    }

    public function studentAuthLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials  = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials, $request->filled('remember'))) 
            {
                $student = auth('student')->user();

                if (auth('student')->user()->force_change_student_password){
                    return redirect()->route('student.password.change');
                }
                return redirect()->route('student.studentDash');
            }
            
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect()->route('student.login');
    }
}
