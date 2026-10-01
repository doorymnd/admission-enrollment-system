@extends('layouts.adminLayout')

@section('content')

    <div class="main-content">
        <header>
            <h1>Dashboard</h1>
            <p>
                You are using a <strong>{{ ucfirst(auth()->user()->role) }}</strong> Account.
            </p>
            <hr>
        </header>
        

        <section class="cards">
            <a href="{{ route('admin.students.index') }}" class="card">
                <h3>Total Students</h3>
                <p>Manage and view the students enrolled.</p>
            </a>
            <a href="{{ route('admin.faculty') }}" class="card">
                <h3>Total Faculty & Staff</h3>
                <p>Manage the accounts of faculty & staff.</p>
            </a>
            <a href="{{ route('admin.courses.coursesPanel') }}" class="card">
                <h3>Courses</h3>
                <p>Manage school courses</p>
            </a>
            <a href="{{ route('admin.exam.upload') }}" class="card">
                <h3>Total Admission</h3>
                <p>View and Manage the admission.</p>
            </a>       
        </section>


        @if($requests->count() > 0)
        <hr>
        <br>

        <section class="cards">
        <div class="status-card" style="width: 100%;">
        <h3>Student Profile Edit Requests</h3>
        <p>Pending requests for student profile updates.</p>

        <hr>
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
    </section>
    @endif

        <hr>
        <br>

        <section class="cards">
            <div class="status-card">
                <h3>System Status</h3>
                <p><strong>School Year:</strong> {{ $setting->school_year }}</p>
                <p><strong>Semester: </strong>{{ $setting->semester }} </p>                    
                <p class="label">
                    <strong>Enrollment: 
                        <span class="{{ $setting->enrollment_status == 'open' ? 'badge-open' : 'badge-closed' }}">
                            {{ strtoupper($setting->enrollment_status) }}
                        </span>
                    </strong>
                </p>
            </div>
            
        </section>

        <hr>
        <br>
        
    </div>

@endsection
