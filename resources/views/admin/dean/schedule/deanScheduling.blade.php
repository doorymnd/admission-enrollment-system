@extends('layouts.deanLayout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/deanCSS/deanScheduling.css') }}">

<div class="main-content">

    <div class="header-inline">
        <h1>Dean Scheduling</h1>
        <p>Add new schedule available for subjects.</p>

        <div class="p-button-wrapper">
            <a href="{{ route('dean.view.sched') }}" class="btn-view-approved">View Schedule</a>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif


    <form method="POST" action="{{ route('dean.schedules.store') }}" class="schedule-form">
        @csrf

        <div class="form-grid">

            {{-- LEFT SIDE --}}
            <div class="card">

                <h3>Subject & Section</h3>

                {{-- COURSE --}}
                <label>Course</label>
                <select name="course_id" id="courseSelect" required>
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>

                {{-- SUBJECT --}}
                <label>Subject</label>
                <select name="subject_id" id="subjectSelect" required>
                    <option value="">-- Select Subject --</option>
                </select>

                {{-- SECTION --}}
                <label>Section</label>
                <select name="section_id" id="sectionSelect" required>
                    <option value="">-- Select Section --</option>
                </select>

                <label>Faculty</label>
                <select name="faculty_id" required>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}">{{ $faculty->first_name }} {{ $faculty->last_name }} - {{ $faculty->department }}</option>
                    @endforeach
                </select>

            </div>


            {{-- RIGHT SIDE --}}
            <div class="card">

                <h3>Schedule Details</h3>

                <label>Semester</label>
                <input 
                    type="text" 
                    name="semester" 
                    id="semesterSelect"
                    readonly
                    placeholder="Select a subject first"
                    required
                >

                <label>School Year</label>
                <input 
                    type="text" 
                    name="school_year" 
                    value="{{ $schoolYear->school_year ?? '' }}" 
                    readonly 
                    required
                >

                <label>Day</label>
                <select name="day" required>
                    <option value="">-- Select Day --</option>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                </select>

                <div class="time-group">
                    <div>
                        <label>Start Time</label>
                        <input type="time" name="start_time" required>
                    </div>

                    <div>
                        <label>End Time</label>
                        <input type="time" name="end_time" required>
                    </div>
                </div>

                <label>Room</label>
                <input type="text" name="room" placeholder="Room">

            </div>

        </div>

        <button type="submit" class="btn-submit">
            Create Schedule
        </button>

    </form>

</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/deanScheduling.js') }}"></script>

@endsection