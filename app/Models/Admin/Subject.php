<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'course_id',
        'code',
        'name',
        'year_level',
        'semester',
        'units',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class, 'subject_id');
    }


    public function getSemesterNameAttribute()
    {
        if ($this->semester == 1) return '1st Semester';
        elseif ($this->semester == 2) return '2nd Semester';
        elseif ($this->semester == 3) return 'Summer';
        return 'Unknown';
    }

    public function getYearNameAttribute()
    {
        return $this->year_level == 1 ? '1st Year' :
               ($this->year_level == 2 ? '2nd Year' :
               ($this->year_level == 3 ? '3rd Year' : '4th Year'));
    }


    public function faculties()
    {
        return $this->belongsToMany(Faculty::class, 'faculty_subject', 'subject_id', 'faculty_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id');
    }


    public function prerequisites()
    {
        return $this->belongsToMany(
            Subject::class,
            'subject_prerequisites',
            'subject_id',
            'prerequisite_id'
        )->withTimestamps(); // ADD THIS
    }

    
}
