@extends('layouts.adminLayout')

@section('content')

    <div class="main-content">
        <header>
            <h1>Add a new Subject</h1>
            <p>Create a new subject under a program.</p>
        <hr>
        </header>
    

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('admin.subjects.store') }}">
            @csrf

            <div class="form-group">
                <label>Course</label>
                    <select name="course_id" required>
                        <option value="">-- Select Course --</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->code }} - {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group">
                <label>Subject Code</label>
                <input type="text" name="code" class="form-control" placeholder="Subject Code" required>
            </div>

            <div class="form-group">
                <label>Subject Name</label>
                <input type="text" name="name" class="form-control" placeholder="Subject Name" required>
            </div>

            <div class="form-group">
            <label>Year Level</label>
                <select name="year_level" required>
                    <option value="">-- Select Year --</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>

            <div class="form-group">
            <label>Semester</label>
                <select name="semester" required>
                    <option value="">-- Select Year --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">Summer</option>
                </select>
            </div>

            <div class="form-group">
                <label>Units</label>
                <input type="number" name="units" min="1" max="6" required>
            </div>

            <button type="submit" class="btn-upload">Save Subject</button>
        </form>
    </div>
    </div>

@endsection