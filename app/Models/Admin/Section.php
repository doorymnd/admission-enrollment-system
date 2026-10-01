<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';

    protected $fillable = [
        'course_id',
        'year_level',
        'section',
        'semester',
        
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'section_id');
    }
}
