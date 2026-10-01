@extends('layouts.studentLayout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/studentCSS/DASH.css') }}">

@php

    $student = Auth::guard('student')->user();
    $info = $student->info ?? null;

    $latestEnrollment = $student->enrollments->sortByDesc('id')->first();

    $isIrregular = $latestEnrollment && $latestEnrollment->type == 'irregular';

    $setting = \App\Models\Admin\SchoolSetting::first();

    $semester = $setting->semester ?? 1;

    $semesterLabel = $semester == 1
        ? '1st'
        : ($semester == 2 ? '2nd' : 'Summer');

@endphp

<div class="main-content">

    <header>
        <h1>Student Dashboard</h1>
    </header>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <!-- PROFILE SECTION -->
    <div class="profile-summary">

        <div class="profile-left">
            <img
                src="{{ $info && $info->profile_picture 
                        ? asset('storage/' . $info->profile_picture) 
                        : asset('img/user.jpg') }}"
                alt="Profile Picture"
                class="profile-avatar"
            >
        </div>

        <div class="profile-right">
            <h2>
                {{ $info->first_name ?? '' }}
                {{ $info->middle_name ?? '' }}
                {{ $info->last_name ?? '' }}
            </h2>

            <div class="profile-details">

                <div class="profile-row">
                    <div class="profile-col">
                        <strong>Student ID:</strong> {{ $student->student_no }}
                    </div>

                    <div class="profile-col">
                        <strong>Course:</strong> {{ $latestEnrollment->courseInfo->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="profile-row">
                    <div class="profile-col">
                        <strong>Email:</strong> {{ $student->email }}
                    </div>

                    <div class="profile-col">
                        <strong>Section:</strong> {{ $latestEnrollment->section ?? 'N/A' }}
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- DASHBOARD CARDS -->
    <section class="cards">
        <a href="{{ route('student.enrollments') }}" class="card">
            <h3>Enrollments</h3>
            <p>Check your Enrollments.</p>
        </a>

        <a href="{{ route('student.grades') }}" class="card">
            <h3>Grades</h3>
            <p>View your Grades.</p>
        </a>

        <a href="{{ route('student.registration.list') }}" class="card">
            <h3>Registration Form</h3>
            <p>View and Download your Registration Form.</p>
        </a>

        <a href="{{ route('student.change.password') }}" class="card">
            <h3>Change Password</h3>
            <p>View or Change your Password</p>
        </a>
    </section>

    <!-- ENROLL SECTION -->
<div class="enroll-section">

@if($setting && $setting->enrollment_status == 'open')

    {{-- 1. ALREADY ENROLLED THIS SEMESTER --}}
    @if($isEnrolledThisSemester)

        <button class="btn btn-closed" disabled>
            Already Enrolled for {{ $semesterLabel }} Semester
        </button>

    {{-- 2. PENDING REGULAR ENROLLMENT --}}
    @elseif($latestEnrollment && $latestEnrollment->status == 'pending' && !$isIrregular)

        <button class="btn btn-closed" disabled>
            Pending Approval
        </button>

    {{-- 4. IRREGULAR REQUEST STATUS --}}
    @elseif($isIrregular && $irregularStatus == 'pending')

        <button class="btn btn-closed" disabled>
            Pending Approval (Irregular)
        </button>

    @elseif($isIrregular && $irregularStatus == 'approved')

        <button class="btn btn-closed" disabled style="background:green;">
            Approved (Irregular)
        </button>

    @elseif($isIrregular && $irregularStatus == 'rejected')

        <a href="{{ route('show.irregular.enrollment', $student->id) }}">
            <button class="btn btn-save" style="background:red;">
                Resubmit Request
            </button>
        </a>

    {{-- 5. SHOW IRREGULAR BUTTON ONLY IF NO IRREGULAR REQUEST --}}
    @elseif(!$isIrregular && !$latestEnrollment)

        <a href="{{ route('show.irregular.enrollment', $student->id) }}">
            <button class="btn btn-save">
                Select Subjects (Irregular)
            </button>
        </a>

    {{-- 6. REGULAR ENROLL NOW (MAIN FIX) --}}
    @else

        <form method="POST" action="{{ route('enrollment.setPending', $latestEnrollment->id) }}">
            @csrf
            <button class="btn-save">
                Enroll Now (Regular)
            </button>
        </form>

    @endif

@else

    <button disabled>Enrollment Closed</button>

@endif

</div>

</div>

@endsection