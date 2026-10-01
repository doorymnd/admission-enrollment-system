<?php


use App\Http\Controllers\Faculty\FacultyDashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Faculty\FacultyAuthController;

Route::prefix('faculty')->group(function () {

    Route::get('/login', [FacultyAuthController::class, 'showLoginForm'])
        ->name('faculty.login');

    Route::post('/login', [FacultyAuthController::class, 'login'])
        ->name('faculty.login.submit');

    Route::middleware('auth:faculty')->group(function () {

        Route::get('/dashboard', [FacultyDashboardController::class, 'viewFacultyDash'])
            ->name('faculty.facultyDash');
 
        Route::post('/logout', [FacultyAuthController::class, 'logout'])
            ->name('faculty.logout');
    });

});

use App\Http\Controllers\Faculty\FacultyPasswordController;

Route::prefix('faculty')->middleware('auth:faculty')->group(function(){

    Route::get('/create-password', [FacultyPasswordController::class, 'showFacultyChange'])
        ->name('faculty.password.create');

    Route::post('/create-password', [FacultyPasswordController::class, 'storeFacultyChange'])
        ->name('faculty.password.store');
});


use App\Http\Controllers\Faculty\FacultyFunction\GradeController;

Route::prefix('faculty')->middleware('auth:faculty')->group(function () {
    Route::get('/grades', [GradeController::class, 'showForm'])->name('showForm.grades.upload');
    Route::post('/grades/upload', [GradeController::class, 'upload'])->name('faculty.grades.upload');


    Route::post('/grades/update', [GradeController::class, 'updateGrades'])
        ->name('faculty.grades.update');

    Route::post('/grades/submit', [GradeController::class, 'submitGrades'])
        ->name('faculty.grades.submit');

    Route::post('/grades/request-edit-subject/{assignmentId}', [GradeController::class, 'requestEditSubject'])
        ->name('faculty.grades.requestEditSubject');

    Route::get('/faculty/grades/export/{assignment_id}', [GradeController::class, 'exportGrades'])  
        ->name('faculty.grades.export');
});

Route::get('/faculty/grade-upload-status', function () {
    $setting = \App\Models\Admin\SchoolSetting::first();

    return response()->json([
        'enabled' => $setting->grade_upload_enabled ?? false
    ]);
});



use App\Http\Controllers\Faculty\FacultyFunction\FacultyMySubjectsController;

Route::prefix('faculty')->middleware(['auth:faculty'])->group(function() {

    Route::get('/my-subjects', [FacultyMySubjectsController::class, 'mySubjects'])
         ->name('faculty.mySubjects');
});



// VIEW UPLOADED GRADES FACULTY
use App\Http\Controllers\Students\StudentFunction\StudentGradeController;

Route::get('/faculty/grades/view', [StudentGradeController::class, 'viewGrades'])
    ->name('view.uploaded.grades');


use App\Http\Controllers\Faculty\FacultyFunction\FacultyMyStudentsController;

Route::get('/faculty/my-students', [FacultyMyStudentsController::class, 'myStudents'])
    ->name('faculty.myStudents');

Route::get('/faculty/my-students/download', [FacultyMyStudentsController::class, 'downloadStudents'])
    ->name('faculty.downloadStudents');


use App\Http\Controllers\Faculty\FacultyFunction\ChangePasswordController;

Route::get('/faculty/change-pass', [ChangePasswordController::class, 'facultyEdit'])
    ->name('faculty.change.pass');;

Route::post('/faculty/change-pass', [ChangePasswordController::class, 'facultyUpdate'])
    ->name('faculty.change.pass.update');


