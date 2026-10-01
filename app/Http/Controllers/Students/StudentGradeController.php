<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Admin\Grade;

class StudentGradeController extends Controller
{

    public function index()
    {

        $grades = Grade::whereHas('enrollment', function($q){

            $q->where('student_id',auth()->id());

        })
        ->where('status','approved')
        ->get();

        return view('student.viewStudentFunction.grades',compact('grades'));

    }

}