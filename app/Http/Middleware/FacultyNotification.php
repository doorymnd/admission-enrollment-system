<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin\SchoolSetting;

class FacultyNotification
{
    public function handle($request, Closure $next)
    {
        $schoolSetting = SchoolSetting::first();

        if ($schoolSetting && $schoolSetting->grade_upload_enabled) {
            session()->flash('grade_status', 'enabled');
        } else {
            session()->flash('grade_status', 'disabled');
        }

        return $next($request);
    }
}