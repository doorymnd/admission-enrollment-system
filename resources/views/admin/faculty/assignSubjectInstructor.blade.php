@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    @php
        $semesters = [
            1 => 'First',
            2 => 'Second',
            3 => 'Summer'
        ];
    @endphp

    <div class="header-inline">
        <h1>Assigned Subjects</h1>
        <p>View all of the assigned subjects to Instructor.</p>

        <div class="p-button-wrapper">
            <a href="{{ route('assign.course') }}" class="btn-view-approved">Assign Subject to a Instructor</a>
        </div>
    </div>

    <hr>

    <!-- SEARCH BAR -->
    <div style="margin-bottom:15px;">
        <input 
            type="text" 
            id="searchInput" 
            placeholder=" Search instructor, course, subject..." 
            style="width:300px; padding:8px; border:1px solid #ccc; border-radius:5px;"
        >
    </div>

    <!-- TABLE -->
    <table id="assignmentTable" border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>Instructor</th>
                <th>Course</th>
                <th>Subject</th>
                <th>Year Level</th>
                <th>Semester</th>
                <th>Section</th>
            </tr>
        </thead>

        <tbody>
            @forelse($assignments as $assign)
                <tr>
                    <td>
                        {{ $assign->faculty->first_name }},
                        {{ $assign->faculty->last_name }}
                    </td>

                    <td>{{ $assign->course->name }}</td>

                    <td>{{ $assign->subject->name }}</td>

                    <td>{{ $assign->year_level }}</td>

                    <td>
                        {{ $semesters[$assign->semester] ?? 'Unknown' }}
                    </td>

                    <td>{{ $assign->section }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" align="center">No assignments yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script src="{{ asset('js/assignsubjectsearch.js') }}"></script>

@endsection