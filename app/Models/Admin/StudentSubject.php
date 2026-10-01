<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Subject;

class StudentSubject extends Model
{
    protected $table = 'student_subjects';

    protected $fillable = [
        'student_id',
        'subject_id',
        'course_id',
        'year_level',
        'semester',
        'school_year',
        'enrollment_id'
    ];


    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}