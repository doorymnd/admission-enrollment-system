@extends('layouts.studentLayout')

@section('content')
<style>
    
.semester-list {
    list-style: none;
    padding: 0;
    max-width: 100%;
    margin: 20px 0;
}

.semester-list li {
    margin-bottom: 10px;
}

.semester-list a {
    display: block;
    text-decoration: none;
    background-color: #f0f0f0;
    color: #333;
    padding: 12px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.semester-list a:hover {
    background-color: #407e4f;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.semester-list a:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>




<div class="main-content">

 @php
     $semesterNames = [
    1 => 'First Semester',
    2 => 'Second Semester',
    3 => 'Midyear'
];
 @endphp


    <div class="header-inline">
        <h1>Select Semester</h1>
    </div>
    

    <ul class="semester-list">
        @foreach($semesters as $sem)
            <li>
                <a href="{{ route('student.grades.show', [$sem->school_year, $sem->semester]) }}">
                    {{ $sem->school_year }} - {{ $semesterNames[$sem->semester] }}
                </a>
            </li>
        @endforeach
    </ul>

</div>
@endsection