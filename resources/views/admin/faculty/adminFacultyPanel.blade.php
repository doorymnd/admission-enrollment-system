@extends('layouts.adminLayout')

@section('content')

    <div class="main-content">

        <div class="header-inline">
            <h1>Instructor Information</h1>
            <p>View Instructor account information</p>

            <div class="p-button-wrapper">
                <a href="{{ route('assign.course') }}" class="btn-view-approved">Assign Subject to a Instructor</a>
            </div>
        </div>

        <hr>

        <input 
            type="text" 
            id="facultySearch" 
            placeholder="Search faculty..." 
            style="margin-bottom: 15px; padding: 8px; width: 300px;"
        >

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($faculties as $faculty)
                <tr class="faculty-row">
                    <td class="faculty-name">
                        {{ $faculty->last_name }}, {{ $faculty->first_name }}
                    </td>
                    <td class="faculty-role">{{ $faculty->role }}</td>
                    <td class="faculty-department">{{ $faculty->department ?? '—' }}</td>
                    <td>{{ ucfirst($faculty->status) }}</td>
                    <td>
                        <a href="{{ route('admin.faculty.view', $faculty->id) }}" class="btn-approved">
                            View
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

<script>
document.getElementById('facultySearch').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('.faculty-row');

    rows.forEach(row => {
        let name = row.querySelector('.faculty-name').innerText.toLowerCase();
        let role = row.querySelector('.faculty-role').innerText.toLowerCase();
        let department = row.querySelector('.faculty-department').innerText.toLowerCase();

        if (
            name.includes(value) ||
            role.includes(value) ||
            department.includes(value)
        ) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection