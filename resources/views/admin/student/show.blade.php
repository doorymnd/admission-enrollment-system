@extends('layouts.adminLayout')

@section('content')
<div class="student-profile">

    <h2>{{ $student->info->first_name }} {{ $student->info->last_name }}</h2>
    <p>Student No: {{ $student->student_no }}</p>
    <p>Email: {{ $student->email }}</p>
    <p>Phone: {{ $student->info->phone }}</p>
    <p>Address: {{ $student->info->address }}</p>

    <hr>

    <h4>Enrollment Info</h4>
    <p>Course: {{ $student->enrollment->course }}</p>
    <p>Year Level: {{ $student->enrollment->year_level }}</p>
    <p>Section: {{ $student->enrollment->section }}</p>
    <p>Status: {{ ucfirst($student->enrollment->status) }}</p>
    <p>School Year: {{ $student->enrollment->school_year }}</p>

</div>
@endsection
