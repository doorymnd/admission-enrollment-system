@extends('layouts.adminLayout')

@section('content')
<div class="main-content">
    <div class="header-inline">
        <h1>Class Schedule</h1>
        <p>List of all the available schedule.</p>
    </div>
    <hr>
    
<div>
    <input type="text" id="searchInput"
        placeholder="Search subject, instructor, room..."
        style="margin-bottom: 15px; padding: 8px; width: 300px;">
</div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Instructor</th>
                <th>Day</th>
                <th>Time</th>
                <th>Room</th>
                <th>Section</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->subject->code ?? 'N/A' }}</td>
                    <td>{{ $schedule->subject->name ?? 'N/A' }}</td>

                    {{-- Instructor --}}
                    <td>
                        {{ $schedule->faculty->first_name ?? '' }}
                        {{ $schedule->faculty->last_name ?? 'N/A' }}
                    </td>

                    <td>{{ $schedule->day }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                        -
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                    </td>

                    <td>{{ $schedule->room }}</td>
                    <td>{{ $schedule->section ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No class schedule found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('#searchInput').on('keyup', function () {
    let value = $(this).val();

    $.ajax({
        url: "{{ route('admin.schedule.view') }}",
        type: "GET",
        data: { search: value },
        success: function (response) {
            let rows = '';

            response.schedules.forEach(schedule => {
                rows += `
                <tr>
                    <td>${schedule.subject?.code ?? 'N/A'}</td>
                    <td>${schedule.subject?.name ?? 'N/A'}</td>
                    <td>${schedule.faculty?.first_name ?? ''} ${schedule.faculty?.last_name ?? ''}</td>
                    <td>${schedule.day}</td>
                    <td>${schedule.start_time} - ${schedule.end_time}</td>
                    <td>${schedule.room}</td>
                    <td>${schedule.section ?? 'N/A'}</td>
                </tr>`;
            });

            $('tbody').html(rows);
        }
    });
});
</script>
@endsection