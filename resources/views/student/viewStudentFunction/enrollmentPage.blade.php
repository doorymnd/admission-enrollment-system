@extends('layouts.studentLayout')

@section('content')

@php
    $student = Auth::guard('student')->user();
@endphp

<div class="main-content">

    <h2>Student Enrollment Form</h2>

    {{-- SUCCESS / ERROR MESSAGES --}}
    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    {{-- CHECK IF ALREADY PENDING OR ENROLLED --}}
    @php
        $existing = $student->enrollments()
            ->whereIn('status', ['pending', 'enrolled'])
            ->latest()
            ->first();
    @endphp

    @if($existing)
        <div style="padding: 15px; background: #f5f5f5;">
            <h3>Enrollment Status</h3>

            <p>
                Status:
                <strong>
                    {{ ucfirst($existing->status) }}
                </strong>
            </p>

            <p>
                Course:
                {{ $existing->courseInfo->name ?? 'N/A' }}
            </p>

            <p>
                Section:
                {{ $existing->section ?? 'Not Assigned' }}
            </p>

            <p>
                Year Level:
                {{ $existing->year_level }}
            </p>

            <p>You already have an active enrollment request.</p>
        </div>

    @else

        {{-- ENROLLMENT FORM --}}
        <form method="POST" action="{{ route('student.enroll.submit') }}">
            @csrf

            <div>
                <label>Course</label>
                <select name="course_id" required>
                    <option value="">-- Select Course --</option>

                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">
                            {{ $course->name ?? $course->course }}
                        </option>
                    @endforeach
                </select>
            </div>

            <br>

            <div>
                <label>Year Level</label>
                <select name="year_level" required>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>

            <br>

            <div>
                <label>Section (Optional)</label>
                <input type="text" name="section" placeholder="Example: 1BSIT-1">
            </div>

            <br>

            <button type="submit" style="padding:10px 20px;">
                Submit Enrollment
            </button>

        </form>

    @endif

</div>

@endsection