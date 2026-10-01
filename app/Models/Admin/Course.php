<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'program_id',
        'code',
        'name',
        'department',
        'duration_years',
        'section_code',
        'sections_per_year',
        'max_students_per_section',
    ];

    // A course has many subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
