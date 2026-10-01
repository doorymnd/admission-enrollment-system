<?php

namespace App\Models\Admission;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'first_name', 
        'middle_name', 
        'last_name',
        'suffix',
        'email', 
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
        'primary_course', 
        'secondary_course', 
        'tertiary_course', 
        'status',
        'passed_at',
    ];

    protected $casts = [
        'passed_at' => 'datetime',
        'birthdate' => 'date',
    ];



/* ======================
   FORMAT HELPER
====================== */

private function formatName($value)
{
    if (!$value) return null;

    $value = strtolower(trim($value));

    $smallWords = ['de', 'del', 'la', 'di', 'do', 'da', 'dos', 'das', 'y', 'van', 'von'];

    $words = explode(' ', $value);

    foreach ($words as $index => $word) {
        if ($index === 0) {
            $words[$index] = ucfirst($word);
        } else {
            $words[$index] = in_array($word, $smallWords)
                ? $word
                : ucfirst($word);
        }
    }

    return implode(' ', $words);
}

/* ======================
   NAME FIELDS
====================== */

public function setFirstNameAttribute($value)
{
    $this->attributes['first_name'] = $this->formatName($value);
}

public function setMiddleNameAttribute($value)
{
    $this->attributes['middle_name'] = $value ? $this->formatName($value) : null;
}

public function setLastNameAttribute($value)
{
    $this->attributes['last_name'] = $this->formatName($value);
}

public function setSuffixAttribute($value)
{
    $this->attributes['suffix'] = $value ? strtoupper($value) : null;
}

/* ======================
   ADDRESS FIELDS
====================== */

public function setStreetAttribute($value)
{
    $this->attributes['street'] = $this->formatName($value);
}

public function setBarangayAttribute($value)
{
    $this->attributes['barangay'] = $this->formatName($value);
}

public function setMunicipalityAttribute($value)
{
    $this->attributes['municipality'] = $this->formatName($value);
}

public function setCityAttribute($value)
{
    $this->attributes['city'] = $this->formatName($value);
}

/* ======================
   SCHOOL FIELD
====================== */

public function setShsSchoolAttribute($value)
{
    $this->attributes['shs_school'] = $this->formatName($value);
}


    /* ======================
       STATUS HELPERS
    ====================== */

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    //This connects the admission to the created Student account via email.
    public function student()
    {
        return $this->hasOne(\App\Models\Admin\Student::class, 'email', 'email');
    }



       public function enrollment()
    {
        return $this->hasOne(Enrollment::class, 'student_id');
    }

}

