<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'phone',
        'street',
        'barangay',
        'municipality',
        'city',
        'birthdate',
        'gender',
        'shs_school',
        'shs_course',
        'gwa',
        'profile_picture',
        'edit_request_status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
