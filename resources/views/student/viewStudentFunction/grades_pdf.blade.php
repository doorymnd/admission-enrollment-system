<!DOCTYPE html>
<html>
<head>
<style>
            body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
        }

        .subtitle {
            font-size: 12px;
        }

        .student-info {
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right
        }

        .footer {
            margin-top: 20px;
        }

        .profile-details {
            margin-top: 15px;
            width: 100%;
        }

        /* Each row behaves like a table row */
        .profile-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        /* Each column behaves like a table cell */
        .profile-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        /* Optional: spacing between left and right */
        .profile-col:first-child {
            padding-right: 10px;
        }
</style>
</head>

<body>
    @php 
        $enrollment = $student->enrollments->first();

        $semesterNames = [ 
            1 => 'First Semester', 
            2 => 'Second Semester', 
            3 => 'Midyear' 
        ]; 

        $totalUnits = 0;
        $totalGrades = 0;
        $gradedCount = 0;
    @endphp
    {{-- HEADER --}}
    <div class="header">
        <div>Republic of the Philippines</div>
        <div>Province of Cavite</div>
        <div class="title">TRECE MARTIRES CITY COLLEGE</div>
        <div class="subtitle">Trece Martires City</div>

        <br>

        <div>{{ $semesterNames[$semester] ?? '' }} S.Y {{ $school_year }}</div>
        <div class="title">COPY OF GRADES</div>
        
    </div>

    <div class="profile-details">
        <div class="profile-row">
            <div class="profile-col">
                <strong>Name:</strong> 
                {{ optional($student->info)->last_name ?? 'TBA' }}, 
                {{ optional($student->info)->first_name ?? '' }} <br>
            </div>

            <div class="profile-col">
                <strong>Course:</strong> {{ optional($enrollment->courseInfo)->name ?? 'N/A' }} <br>
            </div>
        </div>

        <div class="profile-row">
            <div class="profile-col">
                <strong>Student No:</strong>{{ $student->student_no }}<br>
            </div>

            <div class="profile-col">
                <strong>Year Level:</strong> {{ $enrollment->school_year ?? 'N/A' }}
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th>Subject Code</th>
                <th class="left">Subject</th>
                <th>Units</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>

        <tbody>

            @foreach($subjects as $sub)
                @php
                    $grade = $grades[$sub->subject_id] ?? null;
                    $units = $sub->subject->units ?? 0;
                    $totalUnits += $units;

                    if($grade && $grade->grade !== null) {
                        $totalGrades += $grade->grade;
                        $gradedCount++;
                    }
                @endphp

                <tr>
                    <td>{{ $sub->subject->code ?? 'N/A' }}</td>
                    <td class="left">{{ $sub->subject->name ?? 'N/A' }}</td>
                    <td>{{ $grade->grade ?? '-' }}</td>
                    <td>{{ $units }}</td>

                    <td>
                        @if($grade)
                            {{ $grade->grade <= 3 ? 'PASSED' : 'FAILED' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td class="right">
                        <strong>GWA:</strong>
                    </td>
                    <td>
                        <strong> {{ $gradedCount > 0 ? number_format($totalGrades / $gradedCount, 2) : '0.00' }}</strong>
                    </td>

                    <td>
                        <strong>{{ $totalUnits }}</strong>
                    </td>

                    <td></td>

                </tr>
            </tfoot>
    </table>



</body>
</html>