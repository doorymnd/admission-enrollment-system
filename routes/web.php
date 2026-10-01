<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admission\AdmissionController;


Route::get('/', function () {
    return view('index');
});




//ADMISSION SIGNUP
Route::get('/admission', [AdmissionController::class, 'create'])->name('admission.create');
Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');

//REDIRECT MESSAGE
Route::get('/admission=successful', function(){
    return view('admission.redirect');
})->name('redirect');





//STUDENT ROUTES

use App\Http\Controllers\Students\StudentAuthController;


Route::get('/student/login', [StudentAuthController::class, 'viewStudentLogin'])
        ->name('student.login');


Route::post('/student/login', [StudentAuthController::class, 'studentAuthLogin'])->name('student.login.submit');

Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');



use App\Http\Controllers\Students\StudentDashboardController;

Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'dashboard'])
        ->name('student.studentDash');
});

use App\Http\Controllers\Students\StudentForceChangeController;

Route::get('student/password-change', [StudentForceChangeController::class, 'showChangePasswordForm'])
    ->name('student.password.change');
Route::post('student/password-change', [StudentForceChangeController::class, 'updatePassword'])
    ->name('student.password.update');




use App\Http\Controllers\Students\StudentFunction\StudentProfileController;


    Route::middleware('auth:student')->group(function () {
    Route::get('/student/profile', [StudentProfileController::class, 'myProfile'])
    ->name('student.profile');


    Route::put('/student/profile', [StudentProfileController::class, 'updateProfile'])
    ->name('student.profile.update');

    Route::post('/student/profile/request', [StudentProfileController::class, 'requestEdit'])
        ->name('student.profile.request');
    });







use App\Http\Controllers\Students\StudentFunction\StudentGradeController;

Route::get('/student/grades', [StudentGradeController::class, 'index'])->name('student.grades');
Route::get('/student/grades/{year}/{semester}', [StudentGradeController::class, 'showGrades'])
    ->name('student.grades.show');

Route::get('/student/grades/download/{school_year}/{semester}', [StudentGradeController::class, 'downloadGrades'])
    ->name('student.grades.download');





use App\Http\Controllers\Students\EnrollmentController;

Route::prefix('student')->middleware(['auth:student'])->group(function () {


// Student enroll
Route::post('/enroll/{studentId}', [EnrollmentController::class, 'requestEnroll'])
    ->name('student.enroll');

});


use App\Http\Controllers\Students\StudentFunction\StudentEnrollmentController;

Route::prefix('student')->middleware(['auth:student'])->group(function () {

    Route::get('/enrollments', [StudentEnrollmentController::class, 'viewEnrollments'])
        ->name('student.enrollments')
        ->middleware('auth:student');

});



//PDF REGISTRATION FORM
  
use App\Http\Controllers\Students\RegistrationPDFController;

Route::get('/student/registration/pdf/{id}', 
    [RegistrationPDFController::class, 'download']
)->name('student.registration.pdf');

Route::get('/student/registration-forms', 
    [RegistrationPDFController::class, 'registrationList']
)->name('student.registration.list');




use App\Http\Controllers\Students\StudentFunction\ChangePasswordController;

Route::get('/student/change-password', [ChangePasswordController::class, 'edit'])
    ->name('student.change.password');;

Route::post('/student/change-password', [ChangePasswordController::class, 'update'])
    ->name('student.change.password.update');



use App\Http\Controllers\Students\StudentFunction\IrregularEnrollmentController;

Route::get('/student/enrollment/irregular', [IrregularEnrollmentController::class, 'form'])
    ->name('show.irregular.enrollment');
    
Route::post('/student/enrollment/irregular', [IrregularEnrollmentController::class, 'store'])
    ->name('irregular.enrollment.store');




use App\Http\Controllers\Students\EnrollmentPromoteController;


// Route::post('/student/summer/enroll', [EnrollmentPromoteController::class, 'enrollSummer'])
//     ->name('student.summer.enroll');

Route::post('/enrollment/pending/{id}', [EnrollmentPromoteController::class, 'setPending'])
    ->name('enrollment.setPending');



use App\Http\Controllers\Admin\EnrollmentPromotionController;
Route::post('/student/promote-year/{id}', [EnrollmentPromotionController::class, 'promoteYear'])
    ->name('student.promote.year');


Route::post('/student/summer/enroll', [EnrollmentPromoteController::class, 'enrollSummer'])
    ->name('student.midyear.enroll');