@extends('layouts.adminLayout')

@section('content')
<div class="main-content">

    <h1>Edit Course</h1>

    <form method="POST" action="{{ route('admin.courses.update', $course) }}">
        @csrf
        @method('PUT')

        <label>Course Code</label>
        <input type="text" name="code" value="{{ $course->code }}" required>

        <label>Course Name</label>
        <input type="text" name="name" value="{{ $course->name }}" required>

        <label>Duration (Years)</label>
        <input type="number" name="duration_years" value="{{ $course->duration_years }}">

        <button type="submit" class="btn-upload">Update</button>
    </form>

</div>
@endsection
