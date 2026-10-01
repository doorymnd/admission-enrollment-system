<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\AdminLog;


class AdminAuthController extends Controller
{
public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    // 🔐 ATTEMPT LOGIN FIRST
    if (Auth::guard('admin')->attempt([
    'email' => $request->email,
    'password' => $request->password
])) {

    $admin = Auth::guard('admin')->user();

    // 🔥 LOG LOGIN (ONLY admin + super_admin)
    if (in_array($admin->role, ['admin', 'super_admin'])) {
        $this->logAction(
            $admin->id,
            'login',
            'Admin logged in'
        );
    }

    if ($admin->must_change_password) {
        session([
            'post_password_redirect' => $admin->role
        ]);

        return redirect()->route('admin.change-password.show');
    }

    if (strtolower(trim($admin->role)) === 'dean') {
        return redirect()->route('dean.dashboard');
    }

    if ($admin->role === 'super_admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('admin.dashboard');
}


    return back()
        ->withInput($request->only('email'))
        ->with('error', 'Invalid email or password.');
}

    public function logout()
{
    $admin = Auth::guard('admin')->user();

    if ($admin && in_array($admin->role, ['admin', 'super_admin'])) {
        $this->logAction(
            $admin->id,
            'logout',
            'Admin logged out'
        );
    }

    Auth::guard('admin')->logout();

    return redirect()->route('admin.login');
}


    private function logAction($adminId, $action, $description = null)
    {
        AdminLog::create([
            'admin_id' => $adminId,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}