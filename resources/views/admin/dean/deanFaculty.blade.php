@extends('layouts.deanLayout')

@section('content')

<div class="main-content">

        <div class="header-inline">
            <h1>Instructor Information</h1>
            <p>View Instructor account information</p>

        </div>

        <hr>

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
                <tr>
                    <td>{{ $faculty->last_name }}, {{ $faculty->first_name }}</td>
                    <td>{{ $faculty->role }}</td>
                    <td>{{ $faculty->department ?? '—' }}</td>
                    <td>{{ ucfirst($faculty->status) }}</td>
                    <td>
                        <form method="POST" action="{{ route('dean.faculty.toggle', $faculty->id) }}">
                            @csrf
                            <button type="submit" class="delete-btn">
                                {{ $faculty->status === 'active' ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection