<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'url',
        'read_at'
    ];
}