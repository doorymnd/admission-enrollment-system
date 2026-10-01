@extends('layouts.adminLayout')

@section('content')


@php
    // Map year_level integer to label
    $yearLabels = [
        1 => '1st',
        2 => '2nd',
        3 => '3rd',
        4 => '4th'
    ];
@endphp


<div class="main-content">
    <header>
        <h1>Manage Students</h1>
        <p>Manage all enrolled Students</p>
    </header>

    {{-- <a href="" class="btn-add">Add New Student</a> --}}

    <form method="GET" action="{{ route('admin.students.index') }}" class="filter-form">
        <input type="text" name="search" placeholder="student no, name or email" value="{{ request('search') }}">
        <select name="year_level">
            <option value="">All Year Levels</option>
            <option value="1st Year" {{ request('year_level')=='1st Year' ? 'selected' : '' }}>1st Year</option>
            <option value="2nd Year" {{ request('year_level')=='2nd Year' ? 'selected' : '' }}>2nd Year</option>
            <option value="3rd Year" {{ request('year_level')=='3rd Year' ? 'selected' : '' }}>3rd Year</option>
            <option value="4th Year" {{ request('year_level')=='4th Year' ? 'selected' : '' }}>4th Year</option>
        </select>
        <input type="text" name="section" placeholder="Section" value="{{ request('section') }}">
        <button type="submit">Filter</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-cancel">Reset</a>
    </form>

    {{-- Success Message --}}
    @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif

    {{-- Students Table --}}
    <table class="students-table">
        <thead>
            <tr>
                <th>Student No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Section</th>
                <th>Year Level</th>
                <th style="padding-left: 43px">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($students as $student)
            <tr>
                {{-- Access first_name and last_name from student_infos --}}
                <td>{{ $student->student_no }}</td>
                <td>{{ $student->info->first_name ?? '' }} {{ $student->info->last_name ?? '' }}</td>
                <td>{{ $student->email }}</td>
                {{-- Access section and year_level from enrollments --}}
                <td>{{ optional($student->latestEnrollment)->section }}</td>

                <td>
                    {{ $yearLabels[optional($student->latestEnrollment)->year_level] ?? '' }} Year
                </td>
                
                <td class="actions">
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn-actionedit">Edit</a>
                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;">No students found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

@endsection







