@extends('layouts.deanLayout')

@section('content')

<div class="main-content">

    <div class="header-inline">
        <h1>Grades Approval</h1>
        <p>You can approved the uploaded grades per section.</p>
    </div>
    <hr><br>

    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif
<form method="GET" action="{{ route('dean.grades.approval') }}" style="margin-bottom:20px;">

    <!-- Section -->
    <input type="text" name="section"
        placeholder="Section"
        value="{{ request('section') }}"
        style="padding:8px; width:150px;">

    <!-- Year -->
    <select name="year" style="padding:8px;">
        <option value="">All Year</option>
        <option value="1" {{ request('year') == '1' ? 'selected' : '' }}>1st Year</option>
        <option value="2" {{ request('year') == '2' ? 'selected' : '' }}>2nd Year</option>
        <option value="3" {{ request('year') == '3' ? 'selected' : '' }}>3rd Year</option>
        <option value="4" {{ request('year') == '4' ? 'selected' : '' }}>4th Year</option>
    </select>

    <!-- Instructor -->
    <input type="text" name="instructor"
        placeholder="Instructor"
        value="{{ request('instructor') }}"
        style="padding:8px; width:150px;">

    <button type="submit" class="btn btn-approved">Filter</button>

    <!-- Reset -->
    <a href="{{ route('dean.grades.approval') }}" class="btn btn-approved">Reset</a>

</form>

    <div style="margin:15px 0;">
        <a href="{{ route('dean.grades.approval') }}" class="btn-all">All</a>
        <a href="{{ route('dean.grades.approval', ['status'=>'submitted']) }}" class="btn-pending">Pending</a>
        <a href="{{ route('dean.grades.approval', ['status'=>'approved']) }}" class="btn-approved">Approved</a>
        <a href="{{ route('dean.grades.approval', ['status'=>'released']) }}" class="btn-released">Released</a>
    </div>


    @foreach ($grades as $sectionKey => $subjects)
    
        @php
            $firstGroup = collect($subjects)->first();
            $firstGrade = is_iterable($firstGroup) ? collect($firstGroup)->first() : null;
        @endphp

        @if(!$firstGrade)
            @continue
        @endif

        @php
            $section = $firstGrade->section;
            $semester = $firstGrade->semester;
            
            $faculty = optional($firstGrade->faculty);

            $facultyName = $faculty->first_name && $faculty->last_name
                ? $faculty->first_name . ' ' . $faculty->last_name
                : 'No Instructor';
        @endphp

        <div style="margin-bottom:30px; border:2px solid #ccc; border-radius:8px; padding:15px;">

            <h4 style="background:#25770a; padding:10px; color: white; text-align: center;">
                Section: {{ $section }} | Semester: {{ $semester }} | Instructor: {{ $facultyName }}
            </h4>     
            <hr>   

                @foreach ($subjects as $subjectId => $group)
                    @if(!is_iterable($group))
                        @continue
                    @endif

                    @php
                        $group = collect($group);
                        $first = $group->first();
                    @endphp

                    @if(!$first)
                        @continue
                    @endif

                    @php
                        $subjectName = optional($first->subject)->name ?? 'No Subject';
                        $subjectCode = optional($first->subject)->code ?? 'No Code';
                        $status = $first->status;
                    @endphp

                    <h3 style="background:#bbefaa; padding:10px;">
                        {{ $subjectCode }} | {{ $subjectName }}
                    </h3>

                    <div class="subject-header">
                        @if($status == 'edit_request')
                            <form id="unlockForm-{{ $section }}-{{ $subjectId }}"
                                method="POST"
                                action="{{ route('dean.grades.unlock-subject', [$section,$semester,$subjectId]) }}"
                                style="display:inline;">
                                @csrf
                                <button type="button"
                                    class="btn-pending"
                                    onclick="confirmUnlock('{{ $section }}','{{ $subjectId }}')">
                                    Unlock for Editing
                                </button>
                            </form>
                        @endif


                        @if($status == 'submitted')
                            <form method="POST"
                                action="{{ route('dean.grades.approved-subject', [$section,$semester,$subjectId]) }}"
                                style="display:inline;">
                                @csrf
                                <button class="btn-approve">Approve Subject</button>
                            </form>
                        @endif


                        @if($status == 'approved')
                            <form method="POST"
                                action="{{ route('dean.grades.release-subject', [$section,$semester,$subjectId]) }}"
                                style="display:inline;">
                                @csrf
                                <button class="btn-release">Release Subject</button>
                            </form>
                        @endif

                        <form method="POST"
                            action="{{ route('dean.grades.return-subject', [$section,$semester,$subjectId]) }}"
                            style="display:inline;">
                            @csrf

                            <select name="note" required style="padding:6px; width:220px;">
                                <option value="">Select Reason</option>
                                <option value="Incorrect computation of grades">Incorrect computation of grades</option>
                                <option value="Missing student grades">Missing student grades</option>
                                <option value="Wrong grading format">Wrong grading format</option>
                                <option value="Incomplete submission">Incomplete submission</option>
                                <option value="Data inconsistency">Data inconsistency</option>
                            </select>

                            <button class="btn-pending" style="background:red; color:white;">
                                Return to Instructor
                            </button>
                        </form>
                    </div>



                    <table border="1" width="100%" cellpadding="8">
                            <tr>
                                <th>Student No</th>
                                <th>Name</th>
                                <th>Raw Grade</th>
                                <th>Final Grade</th>
                                <th>Status</th>
                            </tr>

                            @foreach($group as $grade)
                            <tr>
                                <td>{{ optional($grade->student)->student_no ?? 'N/A' }}</td>

                                <td>
                                    {{ optional(optional($grade->student)->info)->first_name ?? '' }}
                                    {{ optional(optional($grade->student)->info)->last_name ?? '' }}
                                </td>

                                <td>{{ $grade->raw_grade }}</td>
                                <td>{{ $grade->final_grade }}</td>
                                    <td>
                                        @if($grade->status === 'submitted')
                                            <span style="color:orange;">Waiting for Approval</span>

                                        @elseif($grade->status === 'approved')
                                            <span style="color: green">Ready for release</span>

                                        @elseif($grade->status === 'released')
                                            <span style="color:rgb(0, 81, 255);">Released</span>

                                        @elseif($grade->status === 'edit_request')
                                            <span style="color: orange;">Waiting</span>

                                        @else
                                            <span style="color:red;">Locked</span>
                                        @endif
                                    </td>
                            </tr>
                        @endforeach

                    </table>
                    <br>
                    <hr>

        @endforeach

    </div>

    @endforeach

</div>
<script src="{{ asset('js/adminGradesPopUp.js') }}"></script>

<script>
document.getElementById('liveSearch').addEventListener('keyup', function () {

    let search = this.value.toLowerCase();

    // each section block
    let sections = document.querySelectorAll('.section-block');

    sections.forEach(section => {

        let text = section.innerText.toLowerCase();

        if (text.includes(search)) {
            section.style.display = '';
        } else {
            section.style.display = 'none';
        }

    });

});
</script>

@endsection