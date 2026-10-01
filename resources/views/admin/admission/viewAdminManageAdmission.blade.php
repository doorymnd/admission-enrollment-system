@extends('layouts.adminLayout')

@section('content')
<div class="main-content">

    <div class="header-inline">
        <h1>Manage Admissions</h1>
        <p>List of all registered applicants</p>

        <div class="p-button-wrapper">
            <a href="{{ route('admissions.download') }}" class="btn-view-approved">
                Download Admissions List
            </a>
        </div>
    </div>
    <hr>

    @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif

<form method="GET" action="{{ route('admin.admissions.index') }}" class="filter-form">

<select name="course" required>
    <option value="">Select Course</option>
    @foreach ($courses as $course)
        <option value="{{ $course->code }}"
                {{ request('course') == $course->code ? 'selected' : '' }}>
            {{ $course->name }} ({{ $course->code }})
        </option>
    @endforeach
</select>

    <input type="date" name="exam_date" value="{{ request('exam_date') }}" required>

    <input type="time" name="exam_time" value="{{ request('exam_time') }}" required>

    <button type="submit" class="btn-filter">Filter</button>

</form>


    <table class="students-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Sex</th>
                <th>Status</th>
                <th>Applied At</th>
                {{-- <th>Action</th> --}}
            </tr>
        </thead>
        <tbody>
        @forelse($admissions as $admission)
            <tr>
                <td>{{ $admission->first_name }} {{ $admission->last_name }}</td>
                <td>{{ $admission->email }}</td>
                <td>{{ $admission->phone }}</td>
                <td>{{ $admission->gender }}</td>
                <td>
                    <span class="status {{ $admission->status }}">
                        {{ ucfirst($admission->status) }}
                    </span>
                </td>
                <td>{{ $admission->created_at->format('M d, Y') }}</td>

            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;">No admissions found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    @if(request('course') && request('exam_date') && request('exam_time'))

<form method="POST" action="{{ route('admin.exams.send') }}" style="display:inline;">
    @csrf

    <input type="hidden" name="course" value="{{ request('course') }}">
    <input type="hidden" name="exam_date" value="{{ request('exam_date') }}">
    <input type="hidden" name="exam_time" value="{{ request('exam_time') }}">

    <button type="submit" class="btn-email">
        Send Exam Email
    </button>
</form>

@endif

</div>
@endsection
