@extends('layouts.deanLayout')

@section('content')

<div class="main-content">

    <!-- HEADER -->
    <div class="header-inline">
        <h1>Dean Dashboard</h1>
        <p>Welcome, {{ Auth::guard('admin')->user()->first_name ?? 'Dean' }}!</p>
    </div>

    <hr><br>

    <div class="dashboard-cards" style="display:flex; gap:20px; flex-wrap:wrap;">

        <div class="card" style="padding:20px; background:#f4f4f4; border-radius:10px; width:200px;">
            <h3 style="text-align: center">Grades</h3>
            <p>You can approved the uploaded grades per section.</p>
        </div>

        <div class="card" style="padding:20px; background:#f4f4f4; border-radius:10px; width:200px;">
            <h3 style="text-align: center">Add Instructor</h3>
            <p>Add new instructor per department.</p>
        </div>

        <div class="card" style="padding:20px; background:#f4f4f4; border-radius:10px; width:200px;">
            <h3 style="text-align: center">Faculty Staff</h3>
            <p>View all of the instructor.</p>
        </div>

    </div>

    


</div>

@endsection