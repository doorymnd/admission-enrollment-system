@extends('layouts.adminLayout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/studentCSS/myProfile.css') }}">

<div class="main-content">

    <div class="profile-container">
        
        <h1>Assigning Subjects</h1>
        <p>This form is for assigning subjects to instructors per section.</p>
        
        <hr>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.assign.course') }}">
            @csrf

            <!-- Department -->
            <label>Select Department</label>
            <select name="department" id="department-select" required>
                <option value="">-- Select Department --</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->department }}">
                        {{ $dept->department }}
                    </option>
                @endforeach
            </select>

            <!-- Course -->
            <label>Select Course</label>
            <select name="course_id" id="course-select" required>
                <option value="">-- Select Course --</option>
            </select>

            <!-- Year Level -->
            <label>Year Level</label>
            <select name="year_level" id="year-level" required>
                <option value="">-- Select Year --</option>
            </select>

            <!-- Semester -->
            <label>Semester</label>
            <select name="semester" id="semester" required>
                <option value="">-- Select Semester --</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
                <option value="3">Summer</option>
            </select>

            <!-- Section -->
            <label>Section</label>
            <select name="section" id="section-select" required>
                <option value="">-- Select Section --</option>
            </select>

            <label>Academic Year</label>
            <select name="academic_year" required>
                <option value="">-- Select Academic Year --</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <hr>

            <!-- Subjects Table -->
            <h3>Assign Instructors</h3>
            <table border="1" width="100%" id="subject-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- JS LOADED --}}
                </tbody>
            </table>

            <button type="submit" style="margin-top:15px;">Assign Subjects</button>

        </form>

    </div>

</div>



<script>

    document.getElementById('department-select').addEventListener('change', function() {
    let dept = this.value;

    if(!dept){
        document.getElementById('course-select').innerHTML = '<option value="">-- Select Course --</option>';
        return;
    }

    fetch(`/get-courses/${dept}`)
    .then(res => res.json())
    .then(data => {
        let courseSelect = document.getElementById('course-select');
        courseSelect.innerHTML = '<option value="">-- Select Course --</option>';

        data.forEach(course => {
            courseSelect.innerHTML += `<option value="${course.id}">${course.name}</option>`;
        });
    });
});

// Fetch Years
document.getElementById('course-select').addEventListener('change', function() {
    let courseId = this.value;

    if(!courseId) return;

    fetch(`/get-years/${courseId}`)
    .then(res => res.json())
    .then(data => {
        let yearSelect = document.getElementById('year-level');
        yearSelect.innerHTML = '<option value="">-- Select Year --</option>';

        data.forEach(year => {
            yearSelect.innerHTML += `<option value="${year}">${year} Year</option>`;
        });
    });
});

// 🔥 IMPORTANT: Trigger BOTH sections + subjects
document.getElementById('semester').addEventListener('change', triggerLoad);
document.getElementById('year-level').addEventListener('change', triggerLoad);
document.getElementById('course-select').addEventListener('change', triggerLoad);

function triggerLoad() {
    loadSections();
    loadSubjects();
}

// Load Sections
function loadSections() {
    let course_id = document.getElementById('course-select').value;
    let year_level = document.getElementById('year-level').value;
    let semester = document.getElementById('semester').value;

    if(!course_id || !year_level || !semester) return;

    fetch(`/get-sections?course_id=${course_id}&year_level=${year_level}&semester=${semester}`)
    .then(res => res.json())
    .then(data => {
        let sectionDropdown = document.getElementById('section-select');
        sectionDropdown.innerHTML = '<option value="">-- Select Section --</option>';

        data.forEach(section => {
            sectionDropdown.innerHTML += `<option value="${section.section}">${section.section}</option>`;
        });
    });
}

// Load Subjects
function loadSubjects() {
    let course_id = document.getElementById('course-select').value;
    let year_level = document.getElementById('year-level').value;
    let semester = document.getElementById('semester').value;

    if(!course_id || !year_level || !semester) return;

    fetch(`/get-subjects?course_id=${course_id}&year_level=${year_level}&semester=${semester}`)
    .then(res => res.json())
    .then(data => {
        console.log("Subjects:", data); // ✅ DEBUG

        let tbody = document.querySelector('#subject-table tbody');
        tbody.innerHTML = '';

        if(data.length === 0){
            tbody.innerHTML = `<tr><td colspan="2">No subjects found</td></tr>`;
            return;
        }

        data.forEach(subject => {
            let row = `
                <tr>
                    <td>${subject.name}</td>
                    <td>
                        <select name="faculty[${subject.id}]" required>
                            <option value="">Select Instructor</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">
                                    {{ $faculty->department }} - {{ $faculty->first_name }}, {{ $faculty->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    })
    .catch(err => console.error("Error loading subjects:", err));


}

    document.getElementById('semester').addEventListener('change', loadSubjects);
document.getElementById('year-level').addEventListener('change', loadSubjects);
document.getElementById('course-select').addEventListener('change', loadSubjects);
</script>

@endsection