<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminChangePassword extends Controller
{


    public function showChangePassword()
    {
        return view('admin.accounts.changePassword');
    }

    public function updateChangePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        $admin->password = Hash::make($request->password);
        $admin->force_password_change = false;
        $admin->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Password updated successfully.');
    }

}
