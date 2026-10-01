<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['code', 'name'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
