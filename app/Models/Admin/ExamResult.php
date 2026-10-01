<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Student;
class ExamResult extends Model
{
    protected $table = 'exam_results'; // make sure this is your table name

    protected $fillable = [
        'student_id',
        'exam_type', // optional
        'score',     // optional
        'status',    // 'passed' or 'failed'
    ];

    // Relation to student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
