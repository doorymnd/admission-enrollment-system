@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <h2>Instructor Details</h2>
    <hr>

    <p><strong>Instructor: </strong>{{ $faculty->last_name }}, {{ $faculty->first_name }}<p>
    <p><strong>Department:</strong> {{ $faculty->department }}</p>

    <hr>

    <h3>Subjects Handled</h3>

    <table>
        <thead>
            <tr>
                <th>Course</th>
                <th>Code</th>
                <th>Subject Name</th>
                <th>Year Level</th>
                <th>Semester</th>
                <th>Section</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $subject)
                <tr>
                    <td>{{ $subject->course ?? '—' }}</td>
                    <td>{{ $subject->code ?? '—' }}</td>
                    <td>{{ $subject->name }}</td>
                    <td>{{ $subject->year_level ?? '—' }}</td>
                    <td>{{ $subject->semester ?? '—' }}</td>
                    <td>{{ $subject->section ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No subjects assigned.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection