<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\SchoolSetting;
use App\Models\Admin\Enrollment;


class Student extends Authenticatable
{
    protected $fillable = [
        'student_no',   
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationship to StudentInfo
    public function info()
    {
        return $this->hasOne(StudentInfo::class, 'student_id');
    }

    // Relationship to Enrollment
    public function enrollment()
    {
        return $this->hasOne(Enrollment::class, 'student_id');
    }

    public function latestEnrollment()
    {
        return $this->hasOne(Enrollment::class)->orderByDesc('year_level');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class, 'student_id', 'student_no');
    }







    // SAVES NG STUDENT NUMBER FOR STUDENT TABLE SA DATA BASE
    protected static function booted()
    {
        static::creating(function ($student) {

            if(empty($student->student_no)) {

                $schoolYear = SchoolSetting::where('enrollment_status', 'open')->first(); 
                
                if (!$schoolYear) 
                { 
                    throw new \Exception('No active school year found in school_settings!'); 
                } 


                $yearFull = explode('-', $schoolYear->school_year)[0];
                $year = substr($yearFull, -2); //FETCH ng last two digit sa year XX26 for student number

            // Lock and get last student_no for this year
                $lastStudent = DB::table('students')
                    ->where('student_no', 'like', 'A' . $year . '-%')
                    ->orderBy('student_no', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($lastStudent) {
                    $lastNumber = (int) substr($lastStudent->student_no, 4);
                    $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $nextNumber = '0001';
                }

                $student->student_no = 'A' . $year . '-' . $nextNumber;
            }
            });
    
    }
            
    
}

