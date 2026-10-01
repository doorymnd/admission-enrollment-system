<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';
    protected $fillable = [
        'student_id',
        'subject_id',
        'assignment_id',
        'faculty_id',
        'course',
        'section',
        'year_level',
        'semester',
        'school_year',
        'raw_grade',
        'final_grade',
        'status'
    ];

    const PENDING = 'pending';
    const SUBMITTED = 'submitted';
    const APPROVED = 'approved';
    const RELEASED = 'released';
    const EDIT_REQUEST = 'edit_request';

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class,  'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }


    public function assignment()
    {
        return $this->belongsTo(FacultyAssignment::class, 'assignment_id');
    }
}
