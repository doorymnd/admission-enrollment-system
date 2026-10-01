@extends('layouts.studentLayout')

@section('content')
<div class="main-content">

<h2>Irregular Student Enrollment</h2>

<p>Select subjects you want to enroll this semester.</p>

{{-- ALERTS --}}
@if(session('error'))
    <div style="color:red; margin-bottom:10px;">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div style="color:green; margin-bottom:10px;">
        {{ session('success') }}
    </div>
@endif

@if($hasRequest == 'pending')
    <div style="color:orange; margin-bottom:10px;">
        Your request is pending approval.
    </div>
@elseif($hasRequest == 'approved')
    <div style="color:green; margin-bottom:10px;">
        Your irregular request has been approved.
    </div>
@elseif($hasRequest == 'rejected')
    <div style="color:red; margin-bottom:10px;">
        Your request was rejected. You may resubmit.
    </div>
@endif

<form method="POST" action="{{ route('irregular.enrollment.store') }}">
@csrf

<div style="background:#fff; padding:20px; border-radius:8px;">

<table style="width:100%; border-collapse:collapse;">

    <thead>
        <tr style="background:#f5f5f5;">
            <th style="text-align: center">
                <input type="checkbox" id="selectAll" {{ $hasRequest ? 'disabled' : '' }}>
            </th>
            <th>Code</th>
            <th>Name</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    @foreach($subjects as $subject)
        <tr style="{{ !$subject->can_take ? 'background:#f8d7da;' : '' }}">

            <td style="text-align:center;">
                <input type="checkbox"
                        class="subject-checkbox"
                        name="subjects[]"
                        value="{{ $subject->id }}"
                        {{ (!$subject->can_take || $hasRequest) ? 'disabled' : '' }}>
            </td>

            <td>{{ $subject->code }}</td>
            <td>{{ $subject->name }}</td>

            <td>
                @if($subject->can_take)
                    <span style="color:green; font-weight:bold;">Available</span>
                @else
                    @if($subject->prereq_status == 'missing')
                        <span style="color:orange; font-weight:bold;">
                            Prerequisite (Not Taken)
                        </span>
                    @elseif($subject->prereq_status == 'failed')
                        <span style="color:red; font-weight:bold;">
                            Prerequisite Failed
                        </span>
                    @endif
                @endif
            </td>

        </tr>
    @endforeach

    </tbody>

</table>

</div>

<br>

<button type="submit"
    style="padding:10px 20px; background:#007bff; color:white; border:none; border-radius:5px;"
    {{ $hasRequest ? 'disabled' : '' }}>
    Submit Enrollment
</button>

</form>

</div>

<script>
document.getElementById('selectAll')?.addEventListener('click', function () {
    let checkboxes = document.querySelectorAll('.subject-checkbox:not(:disabled)');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>

@endsection