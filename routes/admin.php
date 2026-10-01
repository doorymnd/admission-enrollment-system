<?php




use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




use App\Http\Controllers\AdminAuthController;

// ADMIN AUTH
Route::get('/admin/login', function () {
    if(auth('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return view('admin.adminLogin');
})->name('admin.login');



Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


Route::get('/dean/dashboard', function () {

    $admin = Auth::guard('admin')->user();

    // 🔐 security check
    if (!$admin || $admin->role !== 'dean') {
        abort(403);
    }

    return view('admin.dean.dashboard');

})->middleware('auth:admin')->name('dean.dashboard');



use App\Http\Controllers\Admin\AdminForceChangePassController;

Route::middleware('auth:admin')->group(function () {

    Route::get('/admin/new-password', [AdminForceChangePassController::class, 'showChangePassword'])
        ->name('admin.change-password.show');

    Route::post('/admin/new-password', [AdminForceChangePassController::class, 'updatePassword'])
        ->name('admin.change-password.update');

});
























use App\Http\Controllers\Admin\StudentController;


Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/manage-students', [StudentController::class, 'index'])->name('admin.students.index');

    Route::get('/students/{id}/view', [StudentController::class, 'show'])->name('admin.students.show');

    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');

    Route::post('/students/{id}/update', [StudentController::class, 'update'])->name('admin.students.update');

    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

});






use App\Http\Controllers\Admin\ManageAdmissionController;
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {

    Route::get('/admissions', 
        [ManageAdmissionController::class, 'index']
    )->name('admin.admissions.index');

    Route::post('/admissions/{id}/approve', 
        [ManageAdmissionController::class, 'approve']
    )->name('admin.admissions.approve');

    Route::post('/admissions/{id}/reject', 
        [ManageAdmissionController::class, 'reject']
    )->name('admin.admissions.reject');

});

use App\Http\Controllers\Admission\AdmissionExamEmailController;

Route::post('/admin/exam/send', [AdmissionExamEmailController::class, 'sendExamEmail'])
    ->name('admin.exams.send');





use App\Http\Controllers\Admin\AdminAdmissionController;
    


    //APPROVED-STUDENTS
Route::middleware('auth:admin')->group(function () {
    


    Route::post('/admin/admissions/import-passed', [AdminAdmissionController::class, 'importPassedStudents'])
        ->name('admin.admissions.import-passed')
        ->middleware('auth:admin');
    // View approved students
Route::get('/approved-students', [AdminAdmissionController::class, 'viewApprovedStudents'])
    ->name('admin.approved.students');


Route::get('/admin/import-progress/{batch}', [AdminAdmissionController::class, 'importProgress'])
    ->name('admin.import.progress');
    });


    Route::get('/import-progress/{id}', function ($id) {
    return \App\Models\ImportProgress\ImportBatch::findOrFail($id);
});




use App\Http\Controllers\Admin\ExamResultController;

Route::get('/exam/upload', [ExamResultController::class, 'viewUploadForm'])
        ->name('admin.exam.upload');









use App\Http\Controllers\Admin\CourseController;

Route::middleware('auth:admin')->prefix('admin')->group(function () {

    Route::get('/courses', [CourseController::class, 'index'])
        ->name('admin.courses.coursesPanel');

    Route::get('/courses/create', [CourseController::class, 'create'])
        ->name('admin.courses.create');

    Route::post('/courses', [CourseController::class, 'store'])
        ->name('admin.courses.store');

    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])
        ->name('admin.courses.edit');

    Route::put('/courses/{course}', [CourseController::class, 'update'])
        ->name('admin.courses.update');

    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
        ->name('admin.courses.destroy');

    Route::get('/admin/courses/filter', [CourseController::class, 'filterCourses'])
    ->name('admin.courses.filter');

});



use App\Http\Controllers\Admin\CurriculumController;
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {

    Route::get('/curriculum/upload', 
        [CurriculumController::class, 'create']
    )->name('admin.curriculum.upload');

    Route::post('/curriculum/upload', 
        [CurriculumController::class, 'store']
    )->name('admin.curriculum.store');

});



use App\Http\Controllers\Admin\SubjectController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/subject/create',
        [SubjectController::class, 'create']
    )->name('curriculum.viewsubject');

    Route::post('/subjects', 
        [SubjectController::class, 'store']
    )->name('subjects.store');






    Route::get('/view-subjects', 
    [SubjectController::class, 'index'])
    ->name('subjects.index');



    Route::get('/subjects/course/{course_id}/year/{year}', [SubjectController::class,'byYear'])
    ->name('subjects.byYear');
});






use App\Http\Controllers\Admin\SectionController;

    Route::get('/assign-sections', [SectionController::class, 'assignSections'])
        ->name('admin.sections.assign');











//ADMIN ACCOUNT CREATION
use App\Http\Controllers\Admin\CreateAdminAccountsController;

Route::prefix('admin')->middleware('auth:admin')->group(function (){


    
    Route::get('/view-accounts', [CreateAdminAccountsController::class, 'viewAdminAcc'])
        ->name('admin.accounts');

    Route::get('/create/accounts', [CreateAdminAccountsController::class, 'createAdminAcc'])
        ->name('create.admin.acc');

    Route::post('/accounts', [CreateAdminAccountsController::class, 'createAdminAccstore'])
        ->name('store.admin.acc');

    Route::get('accounts/{id}/edit', [CreateAdminAccountsController::class, 'editAdminAcc'])
        ->name('edit.admin.acc');

    Route::put('accounts/{id}', [CreateAdminAccountsController::class, 'updateAdminAcc'])
        ->name('update.admin.acc');

    Route::delete('accounts/{id}', [CreateAdminAccountsController::class, 'deleteAdminAcc'])
        ->name('delete.admin.acc');

    
    Route::get('/reset-password', [CreateAdminAccountsController::class, 'viewPasswordReset'])
        ->name('password.reset');

    Route::post('/reset-password/{id}', [CreateAdminAccountsController::class, 'resetPassword'])
        ->name('reset.acc.password');
    
});


// ADMIN DASHBOARD (Protected)
use App\Http\Controllers\Admin\AdminDashboardController;

Route::prefix('admin')->middleware(['auth:admin', 'force.password.change'])->group(function (){
    
    Route::get('/dashboard', [AdminDashboardController::class, 'adminDash'])
        ->name('admin.dashboard');
});

use App\Http\Controllers\Admin\AdminChangePassword;

Route::prefix('admin')->middleware(['auth:admin'])->group(function (){

    Route::get('/change-password', [AdminChangePassword::class, 'showChangePassword'])
        ->name('admin.password.change');

    Route::post('/change-password', [AdminChangePassword::class, 'updateChangePassword'])
        ->name('admin.password.update');
});





//SEMESTER CONTROL (CHANGE SEMESTER)

use App\Http\Controllers\Admin\SemesterSettingsController;

Route::prefix('admin')->middleware('auth:admin')->group( function (){

    Route::get('/change-semester', [SemesterSettingsController::class, 'viewSemSettings'])
        ->name('view.change.settings');

    Route::post('/change-semester', [SemesterSettingsController::class, 'updateSemester'])
        ->name('admin.change.semester');

    Route::post('/admin/new-school-year', [SemesterSettingsController::class, 'newSchoolYear'])
        ->name('admin.new.schoolyear');

    
});



//FACULTY MANAGEMENT

use App\Http\Controllers\Admin\AdminFacultyController;

Route::prefix('admin')->middleware('auth:admin')->group(function (){

    Route::get('/faculty', [AdminFacultyController::class, 'viewAdminFaculty'])
        ->name('admin.faculty');


    Route::get('/admin/faculty/{id}', [AdminFacultyController::class, 'viewFaculty'])
        ->name('admin.faculty.view');

    Route::post('/faculty/{id}/toggle-status', [AdminFacultyController::class, 'toggleStatus'])
        ->name('admin.faculty.toggle');

});




use App\Http\Controllers\Admin\CourseAssignmentController;

Route::get('/admin/assign-course', [CourseAssignmentController::class, 'viewAssignCourse'])
    ->name('assign.course');

Route::get('/get-courses/{department}', [CourseAssignmentController::class, 'getCourses']);

Route::get('/get-subjects', [CourseAssignmentController::class, 'getSubjects']);

Route::get('/get-years/{course_id}', [CourseAssignmentController::class, 'getYears']);

Route::post('/admin/assign-course', [CourseAssignmentController::class, 'assignCourse'])
    ->name('admin.assign.course');

Route::get('/admin/assign-subject-to-instructor',[CourseAssignmentController::class, 'viewAssignments'])
    ->name('admin.assignments.list');

Route::get('/get-faculties', [CourseAssignmentController::class, 'getFaculties'])
    ->name('admin.get.faculties');

Route::get('/get-sections', [CourseAssignmentController::class, 'getSections']);





use App\Http\Controllers\Admin\OldStudentController;

Route::get('/admin/import-old-students', [OldStudentController::class, 'viewOldStudents'])
    ->name('admin.oldStudents');

Route::post('/admin/students/import-old', [OldStudentController::class, 'importOldStudents'])
    ->name('admin.students.importOld');

Route::get('/admin/old_student/download', [OldStudentController::class, 'downloadOldStudent'])
    ->name('admin.download.old_student_header');


use App\Http\Controllers\Admin\ImportSectionController;

Route::get('/sections/import', [ImportSectionController::class, 'showImportForm'])->name('sections.import.form');
Route::post('/sections/import', [ImportSectionController::class, 'import'])->name('sections.import');



use App\Http\Controllers\Admin\GradeApprovalController;

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // MAIN PAGE (with filter)
    Route::get('/grades', [GradeApprovalController::class, 'index'])
        ->name('admin.grades.index');

});






use App\Http\Controllers\Admin\EnrollmentPromotionController;

Route::prefix('admin')->group(function () {

    Route::get('/promote', [EnrollmentPromotionController::class, 'viewPromote'])
        ->name('view.promote');


    Route::post('/promote/year/{id}', [EnrollmentPromotionController::class, 'promoteYear'])
        ->name('admin.promote.year');

    Route::post('/promote/semester/{id}', [EnrollmentPromotionController::class, 'promoteSemester'])
        ->name('admin.promote.semester');

    Route::post('/promote/section/{section}', [EnrollmentPromotionController::class, 'promoteSection'])
        ->name('admin.promote.section');

    Route::post('/promote/section-sem/{section}', [EnrollmentPromotionController::class, 'promoteSectionSemester'])
        ->name('admin.promote.section.semester');

    Route::post('/promote/summer/{id}', [EnrollmentPromotionController::class, 'approveSummer'])
        ->name('admin.promote.summer.approve');


});



// MANAGE ADMISSION LIST DOWNLOADS
Route::get('/admin/admission/download', [ManageAdmissionController::class, 'downloadAdmissionList'])
    ->name('admissions.download');






use App\Http\Controllers\Admin\StudentEditProfileRequestController;

Route::get('/admin/student-edit-request', [StudentEditProfileRequestController::class, 'viewProfileRequest'])
    ->name('admin.student.edit-request');

Route::post('/admin/student-edit-requests/{id}/approve', [StudentEditProfileRequestController::class, 'approve'])
    ->name('admin.requests.approve');

Route::post('/admin/student-edit-requests/{id}/reject', [StudentEditProfileRequestController::class, 'reject'])
    ->name('admin.requests.reject');






use App\Http\Controllers\Admin\Dean\DeanFunctionController;

Route::get('/dean/grades-approval', [DeanFunctionController::class, 'deanGrades'])
    ->name('dean.grades.approval');

Route::post('/grades/approve-subject/{section}/{semester}/{subject}', [DeanFunctionController::class, 'deanApproveSubject'])
    ->name('dean.grades.approved-subject');

Route::post('/dean/grades/return/{section}/{semester}/{subject}',
    [DeanFunctionController::class, 'deanReturnSubject']
)->name('dean.grades.return-subject');

Route::post('/grades/release-subject/{section}/{semester}/{subject}', [DeanFunctionController::class, 'deanReleaseSubject'])
    ->name('dean.grades.release-subject');


Route::post('/grades/unlock/{section}/{semester}/{subject}', [DeanFunctionController::class, 'deanUnlockSubject'])
    ->name('dean.grades.unlock-subject');


// ADD INSTRUCTOR

Route::get('/dean/faculty', [DeanFunctionController::class, 'viewDeanFaculty'])
        ->name('dean.faculty');

    Route::post('/dean/faculty/{id}/toggle-status', [DeanFunctionController::class, 'deanToggleStatus'])
        ->name('dean.faculty.toggle');

Route::get('/dean/faculty/create', [DeanFunctionController::class, 'deanCreateAdminFaculty'])
        ->name('dean.faculty.create');

Route::post('/dean/faculty', [DeanFunctionController::class, 'deanStoreAdminFaculty'])
        ->name('dean.faculty.store');



use App\Http\Controllers\Admin\SubjectPrerequisiteController;

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // SHOW PREREQUISITES PAGE
    Route::get('/subjects/{id}/prerequisites', 
        [SubjectPrerequisiteController::class, 'prerequisitesEdit']
    )->name('admin.subject.prerequisites.edit');

    // SAVE PREREQUISITES
    Route::post('/subjects/{id}/prerequisites', 
        [SubjectPrerequisiteController::class, 'prerequisitesUpdate']
    )->name('admin.subject.prerequisites.update');

});




use App\Http\Controllers\Admin\EnrollmentController;

Route::get('/enrollment', [EnrollmentController::class, 'index']);
Route::post('/enroll', [EnrollmentController::class, 'store']); 
Route::get('/students/search', [EnrollmentController::class, 'search']);



use App\Http\Controllers\Admin\IrregularApprovalController;

Route::get('/admin/irregular/pending', [IrregularApprovalController::class, 'pending'])
    ->name('admin.irregular.pending');

Route::post('/admin/irregular/approve/{id}', [IrregularApprovalController::class, 'approve'])
    ->name('admin.irregular.approve');

Route::post('/admin/irregular/reject/{id}', [IrregularApprovalController::class, 'reject'])
    ->name('admin.irregular.reject');

Route::post('/admin/irregular/approve-all/{studentId}', [IrregularApprovalController::class, 'approveAll'])
    ->name('admin.irregular.approveAll');



use App\Http\Controllers\Admin\StudentSubjectController;

Route::get('/admin/student-subjects/create', [StudentSubjectController::class, 'create'])
    ->name('admin.view.add.subject');

Route::get('/admin/student-subjects/store', [StudentSubjectController::class, 'store']);

use App\Http\Controllers\Admin\AddStudentSubjectController;

Route::get('/admin/student/{id}/add-subjects', [AddStudentSubjectController::class, 'create'])
    ->name('admin.addStudentSubject');
Route::post('/admin/student/{id}/add-subjects', [AddStudentSubjectController::class, 'store']);

Route::delete('/admin/student/{id}/remove-subject', 
    [AddStudentSubjectController::class, 'removeSubject']
);


use App\Http\Controllers\Admin\Dean\DeanSchedulingController;

Route::get('/class-schedules/create', [DeanSchedulingController::class, 'create'])
    ->name('dean.schedule.view');
Route::post('/class-schedules', [DeanSchedulingController::class, 'store'])
    ->name('dean.schedules.store');

Route::get('/dean/get-subjects/{courseId}', [DeanSchedulingController::class, 'getSubjects']);
Route::get('/dean/get-sections/{courseId}', [DeanSchedulingController::class, 'getSections']);

Route::get('/dean/view-schedules', [DeanSchedulingController::class, 'viewSched'])
    ->name('dean.view.sched');


use App\Http\Controllers\Admin\ClassScheduleController;

Route::get('/admin/class-schedules', [ClassScheduleController::class, 'adminViewSched'])
    ->name('admin.schedule.view');


use App\Http\Controllers\Admin\AdminLogController;

Route::get('/admin/logs', [AdminLogController::class, 'index'])
    ->name('admin.logs');