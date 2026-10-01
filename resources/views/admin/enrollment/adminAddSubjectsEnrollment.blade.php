@extends('layouts.adminLayout')

@section('content')

<div class="main-content">
    <div class="header-inline">
        <h2>Add Subjects to Student</h2>
    </div>
    <hr>

    <div style="background:#fff; padding:20px; border-radius:8px;">

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th>Student Number</th>
                    <th>Student Name</th>
                    <th>Section</th>
                    <th>Year</th>
                    <th>Semester</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>{{ $student->student->student_no}}</td>

                    {{-- SAFE NAME DISPLAY --}}
                    <td>
                        {{ $student->first_name }}
                        {{ $student->last_name ?? '' }}
                    </td>

                    <td>{{ optional($student->student->enrollment()->latest()->first())->section }}</td>

                    <td style="text-align: center">{{ optional($student->student->enrollment()->latest()->first())->year_level }}</td>

                    <td style="text-align: center">{{ optional($student->student->enrollment()->latest()->first())->semester }}</td>

                    <td style="text-align: center">
                        <a href="{{ route('admin.addStudentSubject', $student->student->id ) }}" class="btn-view-approved">Add Subject</a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;">
                        No students found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

@endsection