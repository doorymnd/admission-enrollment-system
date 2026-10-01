<?php

namespace App\Models\Admin;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Authenticatable
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'role',
        'department',
        'status',
        'force_password_change',
    ];

    protected $hidden = ['password'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'faculty_subject', 'faculty_id', 'subject_id');
    }
}
