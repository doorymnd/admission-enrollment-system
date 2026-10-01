@extends('layouts.adminLayout')

@section('content')
<div class="main-content">

<div class="header-inline">
    <h1>Manage Courses</h1>
    <p>Manage your entire curriculum and course settings.</p>

    <div class="p-button-wrapper">
        <a href="{{ route('admin.courses.create') }}" class="btn-view-approved">Add Course</a>
        <a href="{{ route('admin.curriculum.viewsubject') }}" class="btn-view-approved">Add Subject</a>
    </div>
</div>



    @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif
    <hr style="border: 1px solid rgb(228, 226, 226)">

    <table class="students-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Name</th>
                <th>Duration</th>
                <th style="padding-left: 43px">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($courses as $course)
            <tr>
                <td>{{ $course->code }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->duration_years}} Years</td>
                <td>
                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn-actionedit">Edit</a>| 
                    <form action="{{ route('admin.courses.destroy', $course) }}"
                          method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this course?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection
