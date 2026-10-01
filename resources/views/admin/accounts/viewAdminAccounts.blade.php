@extends('layouts.adminLayout')

@section('content')

    <div class="main-content">
        <div class="header-inline">
            <h1>Manage Admin Accounts</h1>
            <p>Manage all of the admin accounts available.</p>

            {{-- @if(auth('admin')->user()->role === 'Super Admin') --}}
                <div class="p-button-wrapper">
                    <a href="{{ route('create.admin.acc') }}" class="btn-view-approved">Add Admin</a>
                    <a href="{{ route('password.reset') }}" class="btn-view-approved">Reset Pass</a>
                </div>
            {{-- @endif --}}

        </div>
        <hr>


        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <input 
            type="text" 
            id="adminSearch" 
            placeholder="Search admin (name, email, role...)" 
            style="margin-bottom: 15px; padding: 8px; width: 300px;"
        >

        <table class="students-table">
            <thead>
                <tr>
                    {{-- <th> </th> --}}
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th style="padding-left: 43px">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($admins as $admin)
            <tr class="admin-row">

                <td class="admin-firstname">{{ $admin->first_name }}</td>
                <td class="admin-lastname">{{ $admin->last_name }}</td>
                <td class="admin-email">{{ $admin->email }}</td>
                <td>{{ $admin->created_at->format('M d, Y') }}</td>
                <td class="admin-role">{{ ucfirst($admin->role ?? 'Admin') }}</td>

                <td>
                    @if($admin->status === 'active')
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Disabled</span>
                    @endif
                </td>

                <td class="actions">
                    <a href="{{ route('edit.admin.acc', $admin->id) }}" class="btn-actionedit">Edit</a>

                    <form action="{{ route('delete.admin.acc', $admin->id) }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Delete this admin?')">
                            Delete
                        </button>
                    </form>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No admin accounts found.</td>
            </tr>
            @endforelse
            </tbody>
                
        </table>


    </div>

<script>
document.getElementById('adminSearch').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('.admin-row');

    rows.forEach(row => {
        let firstName = row.querySelector('.admin-firstname').innerText.toLowerCase();
        let lastName = row.querySelector('.admin-lastname').innerText.toLowerCase();
        let email = row.querySelector('.admin-email').innerText.toLowerCase();
        let role = row.querySelector('.admin-role').innerText.toLowerCase();

        if (
            firstName.includes(value) ||
            lastName.includes(value) ||
            email.includes(value) ||
            role.includes(value)
        ) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>


@endsection