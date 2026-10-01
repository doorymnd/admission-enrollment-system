@extends('layouts.adminLayout')

@section('content')

    <div class="main-content">
        
        <header>
            <h1>Approved Students</h1>
            <p>List of students who passed the entrance exam and are now regular students.</p>
        </header>

        <table class="approved-table">
            <thead>
                <tr>
                    <th>Student No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Phone</th>
                    <th>Approved At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvedAdmissions as $admission)
                    <tr>
                        <td>{{ optional($admission->student)->student_no ?? '-' }}</td>
                        <td>{{ $admission->first_name }} {{ $admission->last_name }}</td>
                        <td>{{ $admission->email }}</td>
                        <td>{{ $admission->course }}</td>
                        <td>{{ $admission->phone }}</td>
                        <td>{{ $admission->passed_at ? $admission->passed_at->format('M d, Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

    </div>

@endsection