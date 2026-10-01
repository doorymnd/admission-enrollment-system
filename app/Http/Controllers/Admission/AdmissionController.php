<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission\Admission;

class AdmissionController extends Controller
{
    public function create()
    {
        return view('admission.admissionCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'              => 'required|string|max:191',
            'middle_name'             => 'nullable|string|max:191',
            'last_name'               => 'required|string|max:191',
            'suffix'                  => 'nullable|string|max:20',

            'email'                   => 'required|email|unique:admissions,email',
            'phone'                   => ['required', 'regex:/^09[0-9]{9}$/'],
            
            'street'                  => 'required|string|max:255',
            'barangay'                => 'required|string|max:255',
            'municipality'            => 'required|string|max:255',
            'city'                    => 'required|string|max:255',
            'birthdate'               => 'required|date',
            'gender'                  => 'required|in:Male,Female',

            'shs_school'              => 'required|string|max:255',
            'shs_course'              => 'required|string|max:255',
            'gwa'                     => 'required|numeric|min:70|max:100',

            'primary_course'          => 'required|string',
            'secondary_course'        => 'required|string',
            'tertiary_course'         => 'required|string',

            'privacy_agreement'       => 'accepted',
        ], [
            'phone.regex' => 'Phone must be 11 digits and start with 09 (e.g. 09123456789).'
        ]);

        Admission::create($validated);

        return redirect()->route('redirect')->with('success', 'Your admission form has been submitted.');
    }
}
