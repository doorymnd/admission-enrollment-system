<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Admin\Subject;

class Faculty extends Authenticatable
{

    protected $guard = 'faculty';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'employee_id',
        'password'
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'faculty_subject', 'faculty_id', 'subject_id');
    }

    protected $hidden = ['password'];
}

