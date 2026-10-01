<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('faculty')->check()) {
            return redirect()->route('faculty.facultyDash');
        }
        return view('faculty.facultyLogin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials  = $request->only('email', 'password');

        if (Auth::guard('faculty')->attempt($credentials)) 
            {
                if (auth('faculty')->user()->force_password_change) {
                    return redirect()->route('faculty.password.create');
                }

                if (auth('faculty')->user()->status !== 'active') {
                    Auth::guard('faculty')->logout();
                    return back()->withErrors(['email' => 'Your account is inactive.']);
                }

                return redirect()->route('faculty.facultyDash');
            }
            
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        Auth::guard('faculty')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('faculty.login');
    }
}
