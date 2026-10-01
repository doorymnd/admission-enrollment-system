@extends('layouts.adminLayout')

@section('content')

<div class="main-content">
    <h2>Reset Password</h2>
    <p>Reset the admin password here.</p>
    <hr>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

        <table class="students-table">
            <thead>
                <tr>

                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Password</th>
                </tr>
            </thead>


            <tbody>
                @forelse ($admins as $admin)
                <tr>
                    <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                    <td>{{ $admin->email }}</td>
                    
                    <td>{{ ucfirst($admin->role ?? 'Admin') }}</td>
                    <td>
                        @if($admin->status === 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Disabled</span>
                        @endif
                    </td>
                    <td class="actions">

                        @if (auth('admin')->user()->role === 'Super Admin')
                            
                            <form action="{{ route('reset.acc.password', $admin->id) }}" 
                                method="POST" class="inline-form">

                                @csrf

                                <button class="btn-reset"
                                    onclick="return confirm('Reset password for this admin?')">
                                    Reset
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No admin accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
                
        </table>
</div>
@endsection
