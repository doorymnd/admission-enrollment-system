<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Admin;
class AdminLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'ip_address'
    ];


    public function admin()
{
    return $this->belongsTo(Admin::class, 'admin_id');
}
}