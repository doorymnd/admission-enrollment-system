<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;
use App\Models\Admin\AdminLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPasswordResetMail;
use App\Mail\AdminPasswordMail;

class CreateAdminAccountsController extends Controller
{
    public function viewAdminAcc()
    {
        $admins = Admin::orderBy('created_at', 'desc')->get();

        $admin = auth('admin')->user();

        if ($admin && in_array(strtolower($admin->role), ['admin', 'super_admin'])) {
            AdminLog::create([
                'admin_id' => $admin->id,
                'action' => 'view_admin_accounts',
                'description' => 'Viewed admin accounts list',
                'ip_address' => request()->ip(),
            ]);
        }

        return view('admin.accounts.viewAdminAccounts', compact('admins'));
    }


    public function createAdminAcc()
    {
        $currentAdmin = auth('admin')->user();

        if ($currentAdmin->role !== 'super_admin') {
            abort(403);
        }

        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'open_create_admin_form',
            'description' => 'Opened create admin account page',
            'ip_address' => request()->ip(),
        ]);

        return view('admin.accounts.createAdminAccounts');
    }

    public function createAdminAccstore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:admins,email',
            'role'       => 'required|in:super_admin,dean',
            'department' => 'required_if:role,dean|nullable|string|max:255',
        ]);

        $currentAdmin = auth('admin')->user();

        $password = '@dmin123';

        $admin = Admin::create([
            'first_name' => $request->first_name,
            'middle_name'=> $request->middle_name,
            'last_name'  => $request->last_name,
            'suffix'     => $request->suffix,
            'email'      => $request->email,
            'role'       => $request->role,
            'department' => $request->role === 'dean' ? $request->department : null,
            'password'   => Hash::make($password),
            'status'     => 'active',
            'must_change_password' => true,
        ]);

        // LOG CREATE
        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'create_admin_account',
            'description' => 'Created admin account: ' . $admin->email,
            'ip_address' => request()->ip(),
        ]);

        Mail::to($admin->email)->send(
            new AdminPasswordMail($admin, $password)
        );

        return redirect()->route('admin.accounts')
            ->with('success', 'Admin account created successfully. Password sent to email.');
    }

    public function editAdminAcc($id)
    {
        $admin = Admin::findOrFail($id);

        $currentAdmin = auth('admin')->user();

        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'view_edit_admin_form',
            'description' => 'Opened edit page for admin ID: ' . $id,
            'ip_address' => request()->ip(),
        ]);

        return view('admin.accounts.editAdminAccounts', compact('admin'));
    }

    public function updateAdminAcc(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $currentAdmin = auth('admin')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'   => 'required|string|unique:admins,email,' . $admin->id,
            'status'     => 'required|in:active,disabled',
        ]);

        $admin->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'status'     => $request->status,
        ]);

        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'update_admin_account',
            'description' => 'Updated admin account ID: ' . $id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('admin.accounts')
            ->with('success', 'Admin account updated successfully.');
    }

    public function deleteAdminAcc($id)
    {
        $currentAdmin = auth('admin')->user();
        $admin = Admin::findOrFail($id);

        if ($currentAdmin->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        if ($currentAdmin->id === $admin->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $admin->delete();

        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'delete_admin_account',
            'description' => 'Deleted admin account ID: ' . $id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('admin.accounts')
            ->with('success', 'Admin account deleted successfully.');
    }

    public function viewPasswordReset()
    {
        $admins = Admin::orderBy('created_at', 'desc')->get();

        $currentAdmin = auth('admin')->user();

        if ($currentAdmin->role !== 'super_admin') {
            abort(403);
        }

        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'view_password_reset_page',
            'description' => 'Opened password reset page',
            'ip_address' => request()->ip(),
        ]);

        return view('admin.accounts.passwordReset', compact('admins'));
    }

    public function resetPassword(Request $request, $id)
    {
        $currentAdmin = auth('admin')->user();

        if ($currentAdmin->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $admin = Admin::findOrFail($id);

        if ($currentAdmin->id === $admin->id) {
            return back()->with('error', 'You cannot reset your own password.');
        }

        $newPassword = Str::random(10);

        $admin->password = Hash::make($newPassword);
        $admin->force_password_change = true;
        $admin->save();

        Mail::to($admin->email)->send(
            new AdminPasswordResetMail($admin, $newPassword)
        );

        AdminLog::create([
            'admin_id' => $currentAdmin->id,
            'action' => 'reset_admin_password',
            'description' => 'Reset password for admin ID: ' . $id,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', "Password reset successfully.");
    }
}