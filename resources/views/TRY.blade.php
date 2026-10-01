@extends('layouts.adminLayout')

<style>

    .dashboard-header {
    margin-bottom: 25px;
}

.dashboard-header h1 {
    font-size: 28px;
    margin-bottom: 5px;
}

.dashboard-header p {
    color: #777;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.dashboard-card {
    display: flex;
    align-items: center;
    padding: 25px;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    transition: 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card-icon {
    font-size: 40px;
    margin-right: 20px;
}

.card-info h3 {
    font-size: 14px;
    opacity: 0.9;
}

.card-info h1 {
    font-size: 32px;
    margin-top: 5px;
}

/* Colors */

.blue { background: linear-gradient(45deg, #3498db, #2980b9); }
.green { background: linear-gradient(45deg, #2ecc71, #27ae60); }
.orange { background: linear-gradient(45deg, #f39c12, #e67e22); }
.purple { background: linear-gradient(45deg, #9b59b6, #8e44ad); }


</style>

@section('content')

<div class="main-content">

    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>
            Welcome back! You are logged in as 
            <strong>{{ ucfirst(auth()->user()->role) }}</strong>
        </p>
    </div>

    <div class="dashboard-grid">

        <a href="{{ route('admin.students.index') }}" class="dashboard-card blue">
            <div class="card-icon">🎓</div>
            <div class="card-info">
                <h3>Total Students</h3>
                <h1>{{ $totalStudents }}</h1>
            </div>
        </a>

        <a href="#" class="dashboard-card green">
            <div class="card-icon">👨‍🏫</div>
            <div class="card-info">
                <h3>Total Faculty & Staff</h3>
                <h1>{{ $totalFaculty }}</h1>
            </div>
        </a>

        <a href="{{ route('admin.exam.upload') }}" class="dashboard-card orange">
            <div class="card-icon">📝</div>
            <div class="card-info">
                <h3>Total Admissions</h3>
                <h1>{{ $totalAdmission }}</h1>
            </div>
        </a>

        <a href="{{ route('admin.courses.coursesPanel') }}" class="dashboard-card purple">
            <div class="card-icon">📚</div>
            <div class="card-info">
                <h3>Courses</h3>
                <p>Manage school courses</p>
            </div>
        </a>

    </div>

</div>

@endsection




@extends('layouts.adminLayout')

@section('content')

    <div class="main-content">
        <header>
            <h1>Dashboard</h1>
            <p>
                You are using a <strong>{{ ucfirst(auth()->user()->role) }}</strong> Account.
            </p>
        </header>

        <section class="cards">
            <a href="{{ route('admin.students.index') }}" class="card">
                <h3>Total Students</h3>
                <p>{{ $totalStudents }}</p>
            </a>
            <a href="" class="card">
                <h3>Total Faculty & Staff</h3>
                <p>{{ $totalFaculty }}</p>
            </a>
            <a href="{{ route('admin.courses.coursesPanel') }}" class="card">
                <h3>Courses</h3>
                <p>Manage school courses</p>
            </a>
            <a href="{{ route('admin.exam.upload') }}" class="card">
                <h3>Total Admission</h3>
                <p>{{ $totalAdmission }}</p>
            </a>


            
        </section>

    </div>

@endsection
