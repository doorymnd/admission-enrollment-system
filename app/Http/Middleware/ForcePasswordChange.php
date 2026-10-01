<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            Auth::guard('admin')->check() &&
            Auth::guard('admin')->user()->force_password_change
        ) {
            if (!$request->routeIs('admin.password.change')) {
                return redirect()->route('admin.password.change');
            }
        }

        // if (
        //     Auth::guard('student')->check() &&
        //     Auth::guard('student')->user()->force_change_student_password
        // ) {
        //     if (!$request->routeIs('student.password.change')) {
        //         return redirect()->route('student.password.change');
        //     }
        // }

        return $next($request);
    }
}
