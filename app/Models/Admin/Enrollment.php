<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'course',
        'year_level',
        'semester',
        'section',
        'status',
        'school_year',
        'type',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function courseInfo()
    {
        return $this->belongsTo(Course::class, 'course', 'code', 'course_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'course_id', 'course_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }


}
