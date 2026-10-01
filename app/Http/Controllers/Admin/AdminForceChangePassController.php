<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminForceChangePassController extends Controller
{
    /**
     * SHOW CHANGE PASSWORD FORM
     */
    public function showChangePassword()
    {
        return view('admin.change-password');
    }

    /**
     * UPDATE PASSWORD AND REMOVE FORCE FLAG
     */
public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);

    $admin = auth('admin')->user();

    // ✅ Update password
    $admin->password = Hash::make($request->password);

    // ✅ Disable first login flag
    $admin->must_change_password = 0;

    $admin->save();

    // 🔥 Normalize role
    $role = strtolower(trim($admin->role));

    // 🎯 Redirect based on role
    if ($role === 'dean') {
        return redirect()->route('dean.dashboard')
            ->with('success', 'Password updated successfully.');
    }

    // super_admin goes to admin dashboard
    return redirect()->route('admin.dashboard')
        ->with('success', 'Password updated successfully.');
}
}