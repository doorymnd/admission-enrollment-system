<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class FacultyAssignment extends Model
{
    protected $fillable = [
        'faculty_id',
        'course_id',
        'subject_id',
        'year_level',
        'semester',
        'section',
        'academic_year'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grades()
    {
        return $this->hasMany(\App\Models\Admin\Grade::class, 'assignment_id');
    }
}
