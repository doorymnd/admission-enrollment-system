<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $table = 'school_settings';
    protected $fillable = ['school_year', 'semester', 'enrollment_status', 'max_units'];
}
