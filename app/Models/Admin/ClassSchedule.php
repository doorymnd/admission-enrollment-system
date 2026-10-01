<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $table = 'class_schedules';

    protected $fillable = [
        'subject_id',
        'course_id',
        'section',
        'faculty_id',
        'semester',
        'school_year',
        'day',
        'start_time',
        'end_time',
        'room',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function enrollments()
    {
        return $this->hasMany(StudentSubject::class, 'class_schedule_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

}