@extends('layouts.facultyLayout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/facultySubjects.css') }}">

<div class="main-content">
    <div class="header-inline">
        <h1>My Subjects</h1>
        <p>This is the display of instructor subjects.</p>
    </div>

    <hr>

    {{-- Optional: Show current semester --}}
    <p>
        Showing subjects for 
        <strong>
            {{ $currentSemester == 1 
                ? '1st Semester' 
                : ($currentSemester == 2 
                    ? '2nd Semester' 
                    : 'Summer') 
            }}
        </strong>
    </p>

    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Year Level</th>
                    <th>Semester</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->subject->code }}</td>
                        <td>{{ $assignment->subject->name }}</td>
                        <td>{{ $assignment->year_level }}</td>
                        <td>{{ $assignment->semester }}</td>
                        <td>{{ $assignment->section }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-message">
                            No subjects assigned for the current semester.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection