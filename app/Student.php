<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Admin\Grade;
use App\Models\Admin\Enrollment;
use App\Models\Admin\StudentInfo;


class Student extends Authenticatable
{
    protected $guard = 'student';

    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    protected $hidden = ['password'];

    public function info()
    {
        return $this->hasOne(StudentInfo::class, 'student_id');
    }

    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

        public function grades()
    {
        return $this->hasMany(Grade::class);
    }

        public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }


}