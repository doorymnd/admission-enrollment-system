@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <div class="header-inline">
        <h1>Student Edit Requests</h1>
        <p>Approve the request for the student my profile Permission.</p>
    </div>
    <hr>

@if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Student Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Section</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td>
                        {{ $req->student->student_no }}
                    </td>
                    <td>
                        {{ $req->first_name }} {{ $req->last_name }}
                    </td>
                    <td>
                        {{ $req->student->email ?? 'N/A' }}
                    </td>
                    
                    <td>
                        {{ $req->student->enrollment->section }}
                    </td>
                    
                    <td>
                        @if($req->edit_request_status == 'pending')
                            <span style="color:orange;">Pending</span>
                        @elseif($req->edit_request_status == 'approved')
                            <span style="color:green;">Approved</span>
                        @elseif($req->edit_request_status == 'rejected')
                            <span style="color:red;">Rejected</span>
                        @endif
                    </td>

                    <td>
                        @if($req->edit_request_status == 'pending')
                            <form method="POST"
                                  action="{{ route('admin.requests.approve', $req->id) }}"
                                  style="display:inline;">
                                @csrf
                                <button style="background:green; color:white;">
                                    Approve
                                </button>
                            </form>
|
                            <form method="POST"
                                  action="{{ route('admin.requests.reject', $req->id) }}"
                                  style="display:inline;">
                                @csrf
                                <button style="background:red; color:white;">
                                    Reject
                                </button>
                            </form>
                        @else
                            <span>No action</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection