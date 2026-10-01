@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

<h2>Student Enrollment</h2>

@if(session('message'))
    <div style="background:#dff0d8; padding:10px;">
        {{ session('message') }}
    </div>
@endif

<!-- =========================
     SEARCH INPUT (FULL WIDTH)
========================= -->
<div style="margin-bottom:20px; position:relative;">
    <input 
        type="text" 
        id="student_search_input" 
        placeholder="Search student name or number..."
        style="width:100%; padding:10px;"
        autocomplete="off"
    >

    <div id="student_results" 
     style="position:absolute; width:100%; background:white; border:1px solid #ccc; 
     z-index:999; max-height:200px; overflow-y:auto;">
</div>
</div>

<!-- =========================
     AUTO FILLED INFO
========================= -->
<div style="display:flex; gap:20px; margin-bottom:20px;">

    <div style="flex:1;">
        <label>Student Number</label>
        <input type="text" id="student_no" readonly style="width:100%;">
    </div>

    <div style="flex:1;">
        <label>Student Name</label>
        <input type="text" id="student_name" readonly style="width:100%;">
    </div>

</div>

<!-- =========================
     YEAR & SEMESTER
========================= -->
<div style="display:flex; gap:20px; margin-bottom:20px;">

    <div style="flex:1;">
        <label>Year Level</label>
        <input type="text" id="year_level" readonly style="width:100%;">
    </div>

    <div style="flex:1;">
        <label>Semester</label>
        <input type="text" id="semester" readonly style="width:100%;">
    </div>

</div>

<!-- =========================
     LOAD BUTTON (OPTIONAL)
========================= -->
<form method="GET" action="{{ url('/enrollment') }}">
    <input type="hidden" name="student_id" id="student_id">
    <input type="hidden" name="year_level" id="year_level_hidden">
    <input type="hidden" name="semester" id="semester_hidden">

    <button type="submit">Load Subjects</button>
</form>

<hr>

<!-- =========================
     SUBJECTS
========================= -->
@if(request('student_id'))

<form method="POST" action="{{ url('/enroll') }}">
@csrf

<input type="hidden" name="student_id" value="{{ request('student_id') }}">

<table border="1" cellpadding="10" width="100%">
    <tr>
        <th>Subject</th>
        <th>Units</th>
        <th>Status</th>
        <th>Enroll</th>
    </tr>

@php
$passedSubjects = DB::table('grades')
    ->where('student_id', request('student_id'))
    ->where('status', 'released')
    ->where('final_grade', '<=', 3.00)
    ->pluck('subject_id');
@endphp

@foreach($subjects as $subject)

    @php
        $missing = $subject->prerequisites
            ->pluck('id')
            ->diff($passedSubjects);
    @endphp

    <tr>
        <td>{{ $subject->name }}</td>
        <td>{{ $subject->units }}</td>

        @if($missing->isNotEmpty())
            <td style="color:red;">Blocked</td>
            <td><input type="checkbox" disabled></td>
        @else
            <td style="color:green;">Allowed</td>
            <td>
                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}">
            </td>
        @endif
    </tr>

@endforeach

</table>

<br>
<button type="submit">Enroll Selected Subjects</button>

</form>

@endif

</div>


<!-- =========================
     AJAX SEARCH SCRIPT
========================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    console.log("✔ Enrollment JS loaded");

    $('#student_search_input').on('keyup', function () {

        let query = $(this).val();

        console.log("Typing:", query);

        if (query.length < 1) {
            $('#student_results').html('');
            return;
        }

        $.ajax({
            url: "{{ url('/students/search') }}",
            type: 'GET',
            data: { q: query },

            success: function (data) {

                console.log("DATA:", data);

                let html = '';

                data.forEach(student => {
                    html += `
                        <div class="student-item"
                            data-id="${student.id}"
                            data-name="${student.first_name} ${student.last_name}"
                            data-number="${student.student_no}"
                            data-year="${student.year_level}"
                            data-semester="${student.semester}"
                            style="padding:10px; cursor:pointer; border-bottom:1px solid #eee;">
                            ${student.student_no} - ${student.first_name} ${student.last_name}
                        </div>
                    `;
                });

                $('#student_results').html(html);
            },

            error: function (xhr) {
                console.log("AJAX ERROR:", xhr.responseText);
            }
        });
    });

    $(document).on('click', '.student-item', function () {

        console.log("CLICKED");

        let id = $(this).data('id');
        let name = $(this).data('name');
        let number = $(this).data('number');
        let year = $(this).data('year');
        let semester = $(this).data('semester');

        $('#student_search_input').val(number + " - " + name);
        $('#student_results').html('');

        $('#student_id').val(id);
        $('#student_no').val(number);
        $('#student_name').val(name);
        $('#year_level').val(year);
        $('#semester').val(semester);
    });

});
</script>
@endsection