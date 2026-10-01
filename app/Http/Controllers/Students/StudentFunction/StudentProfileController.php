<?php

namespace App\Http\Controllers\Students\StudentFunction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin\Notification;

class StudentProfileController extends Controller
{
    /**
     * Show student profile page
     */
    public function myProfile()
    {
        $student = Auth::guard('student')->user();

        $student->load([
            'info',
            'enrollments.courseInfo'
        ]);

        return view('student.viewStudentFunction.myProfile', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
{
    $student = Auth::guard('student')->user();
    $info = $student->info ?? $student->info()->create([]);

    // ✅ ALWAYS validate image
    $request->validate([
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // ✅ PROFILE PICTURE (ALWAYS ALLOWED)
    if ($request->hasFile('profile_picture')) {

        if ($info->profile_picture &&
            Storage::disk('public')->exists($info->profile_picture)) {
            Storage::disk('public')->delete($info->profile_picture);
        }

        $info->profile_picture = $request->file('profile_picture')
            ->store('profile_pictures', 'public');
    }

    // 🔒 ONLY ALLOW EDIT IF APPROVED
    if ($info->edit_request_status === 'approved') {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'gender'     => 'required|in:Male,Female',
        ]);

        $student->update([
            'email' => $request->email,
        ]);

        $info->update([
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'suffix'      => $request->suffix,
            'phone'       => $request->phone,
            'street'      => $request->street,
            'barangay'    => $request->barangay,
            'municipality'=> $request->municipality,
            'city'        => $request->city,
            'birthdate'   => $request->birthdate,
            'gender'      => $request->gender,
        ]);

        // 🔁 AUTO LOCK AGAIN
        $info->edit_request_status = 'none';
        $info->save();
    }

    return back()->with('success', 'Profile updated.');
}


    public function requestEdit()
{
    $student = Auth::guard('student')->user();
    $info = $student->info;

    $info->edit_request_status = 'pending';
    $info->save();

    // 🔔 CREATE NOTIFICATION FOR ADMIN
    Notification::create([
        'user_id' => null, // or admin_id if you have it
        'type' => 'profile_edit_request',
        'message' => $student->name . ' requested profile edit',
        'url' => route('admin.student.edit-request'),
        'read_at' => null,
    ]);

    return back()->with('success', 'Request sent to admin.');
}
}