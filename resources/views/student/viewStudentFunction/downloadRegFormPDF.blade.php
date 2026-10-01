<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 5px;
        }

        .center { text-align: center; }

        h3 { margin-top: 20px; }

        /* FOOTER SAFE SPACE */
        @page {
            margin-bottom: 60px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.08);
            font-weight: bold;
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>

<body>
<div class="watermark">
    ELECTRONIC COPY
</div>

<h2 style="text-align:center;">Student Enrollment Record</h2>

<div style="margin-bottom: 15px;">

    <!-- ROW 1 -->
    <div style="margin-bottom: 8px;">
        <div style="display:inline-block; width:48%;">
            <strong>Name:</strong>
            {{ optional($enrollment->student->info)->first_name }},
            {{ optional($enrollment->student->info)->last_name }}
        </div>

        <div style="display:inline-block; width:48%;">
            <strong>Year Level:</strong>
            {{ $enrollment->year_level }}
        </div>
    </div>

    <!-- ROW 2 -->
    <div style="margin-bottom: 8px;">
        <div style="display:inline-block; width:48%;">
            <strong>School Year:</strong>
            {{ $enrollment->school_year }}
        </div>
    

        <div style="display:inline-block; width:48%;">
            <strong>Semester:</strong>
            {{ $enrollment->semester == 1 ? 'First Semester' : 'Second Semester' }}
        </div>
    </div>

    <!-- ROW 3 -->
    <div style="margin-bottom: 8px;">
        <div style="display:inline-block; width:48%;">
            <strong>Course:</strong>
            {{ optional($enrollment->courseInfo)->name ?? 'N/A' }}
        </div>
    

</div>

<!-- ===================== -->
<!-- SUBJECTS -->
<!-- ===================== -->
<h3>Subjects</h3>

<table>
    <thead>
        <tr>
            <th>Code</th>
            <th>Subject</th>
            <th>Units</th>
            <th>Time</th>
            <th>Day</th>
            <th>Instructor</th>
        </tr>
    </thead>

    <tbody>

        {{-- ENROLLED --}}
        @forelse($subjects as $sub)

        @php
            $schedule = $scheduleMap[$sub->subject_id] ?? null;
        @endphp
            <tr>
                <td>{{ optional($sub->subject)->code ?? 'N/A' }}</td>
                <td>{{ optional($sub->subject)->name ?? 'N/A' }}</td>
                <td class="center">{{ optional($sub->subject)->units ?? 0 }}</td>
                <td class="center">{{ $schedule->start_time ?? 'N/A' }} - {{ $schedule->end_time ?? 'N/A' }}</td>
                <td class="center">{{ $schedule->day ?? 'N/A' }}</td>
                <td class="center">{{ optional(optional($schedule)->faculty)->first_name ?? 'N/A' }}</td>
            </tr>
        @empty
        @endforelse

        {{-- ADDED --}}
        @forelse($addedSubjects as $sub)

            @php
                $schedule = $scheduleMap[$sub->subject_id] ?? null;
            @endphp
            <tr>
                <td>{{ optional($sub->subject)->code ?? 'N/A' }}</td>
                <td>{{ optional($sub->subject)->name ?? 'N/A' }}</td>
                <td class="center">{{ optional($sub->subject)->units ?? 0 }}</td>
                <td class="center">{{ $schedule->start_time ?? 'N/A' }} - {{ $schedule->end_time ?? 'N/A' }}</td>
                <td class="center">{{ $schedule->day ?? 'N/A' }}</td>
                <td class="center">{{ optional(optional($schedule)->faculty)->first_name ?? 'N/A' }}</td>
            </tr>
        @empty
        @endforelse

        {{-- EMPTY --}}
        @if($subjects->isEmpty() && $addedSubjects->isEmpty())
            <tr>
                <td colspan="4" class="center">No subjects found</td>
            </tr>
        @endif

    </tbody>
</table>

<!-- ===================== -->
<!-- FOOTER -->
<!-- ===================== -->
<div class="footer">
    <hr>
    <strong>Generated on:</strong> |
     {{ \Carbon\Carbon::now()->format('F d, Y') }}
</div>

</body>
</html>