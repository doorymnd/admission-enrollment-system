<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Admission\Admission;
use App\Models\Admin\StudentInfo;
use App\Models\Admin\Enrollment;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
{
    Schema::defaultStringLength(191);

    View::composer('layouts.adminLayout', function ($view) {

        $profileRequestCount = StudentInfo::where('edit_request_status', 'pending')->count();

        $enrollmentPendingCount = Enrollment::where('status', 'pending')->count();

        $irregularEnrollmentCount = Enrollment::where('status', 'pending')
            ->where('type', 'irregular')
            ->count();

        $admissionCount = Admission::where('status', 'pending')->count();

        $view->with([
            'enrollmentPendingCount' => $enrollmentPendingCount,
            'profileRequestCount' => $profileRequestCount,
            'irregularEnrollmentCount' => $irregularEnrollmentCount,
            'admissionCount' => $admissionCount
        ]);
    });
}
}
