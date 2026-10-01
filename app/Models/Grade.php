<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Student;
use App\Models\Admin\Subject;
use App\Models\Admin\Faculty;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'faculty_id',
        'course',
        'section',
        'year_level',
        'semester',
        'school_year',
        'grade',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
