<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{

    protected $guard = 'admin';
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
        'department_id'
    ];

    protected $casts = [
        'force_password_change' => 'boolean',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function isDean()
    {
        return $this->role === 'Dean';
    }

    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'Super Admin';
    }

}
