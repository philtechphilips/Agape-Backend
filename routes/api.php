<?php

use App\Http\Controllers\Admin\Academics;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\Examination;
use App\Http\Controllers\Admin\Profiles;
use App\Http\Controllers\Admin\Schedule;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Main\MainFunctions;
use App\Http\Controllers\Main\StudentApplication;
use App\Http\Controllers\MobileAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(
    function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::delete('/auth/token', [MobileAuth::class, 'destroy']);

        // Dashboard
        Route::get('/dashboard', [Dashboard::class, 'Index']);

        // Dashboard
        // Profile
        Route::post('/create-staff-profile', [Profiles::class, 'AddStaff']);
        Route::post('/create-parent-profile', [Profiles::class, 'AddParent']);
        Route::post('/create-admin-profile', [Profiles::class, 'AddAdmin']);
        Route::post('/create-student-profile', [Profiles::class, 'AddStudent']);
        Route::patch('/student-passport/{id}', [Profiles::class, 'UploadStudentPassport']);
        Route::patch('/update-student/{id}', [Profiles::class, 'UpdateStudent']);

        Route::get('/all-staff', [Profiles::class, 'AllStaff']);
        Route::get('/all-parent', [Profiles::class, 'AllParent']);
        Route::get('/all-admin', [Profiles::class, 'AllAdmin']);
        Route::get('/students/{classes}', [Profiles::class, 'GetStudents']);
        Route::get('/student/{id}', [Profiles::class, 'GetStudentById']);

        Route::delete('/delete-parent/{id}', [Profiles::class, 'DeleteParent']);
        Route::delete('/delete-admin/{id}', [Profiles::class, 'DeleteAdmin']);
        Route::delete('/delete-staff/{id}', [Profiles::class, 'DeleteStaff']);
        // Profile

        //Schedules Routes
        Route::post('/create-event', [Schedule::class, 'AddEvent']);
        Route::get('/events', [Schedule::class, 'Events']);
        // Schedules Route

        // Academics
        Route::post('/add-section', [Academics::class, 'AddSection']);
        Route::get('/sections', [Academics::class, 'GetSection']);
        Route::post('/add-class', [Academics::class, 'AddClass']);
        Route::get('/classes', [Academics::class, 'GetClass']);

        Route::post('/add-subject', [Academics::class, 'AddSubject']);
        Route::get('/subjects', [Academics::class, 'GetSubject']);
        // Route::get('/subjects/{section}', [Academics::class, 'GetSubjectBySection']);
        Route::get('/subjects/{id}', [Academics::class, 'GetSubjectById']);
        Route::delete('/subjects/{id}', [Academics::class, 'DeleteSubject']);

        Route::get('/term', [Academics::class, 'GetTerm']);
        Route::post('/session', [Academics::class, 'AddSession']);
        Route::get('/session', [Academics::class, 'GetSession']);
        Route::get('/session/{id}', [Academics::class, 'GetSessionById']);
        Route::delete('/session/{id}', [Academics::class, 'DeleteSession']);
        // Academics


        // Examination
        Route::get('/exam', [Examination::class, 'GetExam']);
        Route::get('/exam/{id}', [Examination::class, 'GetExamById']);
        Route::post('/first-term-exam', [Examination::class, 'FirstTermResult']);
        Route::post('/mock-exam', [Examination::class, 'MockResult']);
        Route::post('/second-term-exam', [Examination::class, 'SecondTermResult']);
        Route::post('/third-term-exam', [Examination::class, 'ThirdTermResult']);
        Route::get('/get-result/{session}/{class}/{exam}/{subject}', [Examination::class, 'FetchResultToEdit']);
        Route::get('/get-old-result-for-second-term-report/{class}/{exam}/{subject}', [Examination::class, 'FetchFirstTermResultForSecondReport']);
        Route::get('/get-old-result-for-third-term-report/{class}/{exam}/{subject}', [Examination::class, 'FetchSecondTermResultForThirdReport']);
        Route::get('/get-result/{session}/{class}/{exam}', [Examination::class, 'FetchResultData']);
        Route::patch('/first-term-result', [Examination::class, 'UpdateFirstTermResult']);
        Route::patch('/mock-result', [Examination::class, 'UpdateMockResult']);
        Route::patch('/second-term-result', [Examination::class, 'UpdateSecondTermResult']);
        Route::patch('/third-term-result', [Examination::class, 'UpdateThirdTermResult']);
        Route::get('/fetch-report-card', [Examination::class, 'GetReportCard']);
        Route::get('/fetch-result/{stuId}', [Examination::class, 'FetchResult']);
        Route::patch('/result/{id}', [Examination::class, 'UpdateResultStatus']);
        Route::patch('/result/{session}/{class}/{exam}', [Examination::class, 'BulkUpdateResultStatus']);
        Route::patch('/release-single-report-card/{id}', [Examination::class, 'ReleaseSingleReportCard']);
        // Examination

        // Comment
        Route::post('/comment', [Examination::class, 'CreateComment']);
        Route::get('/get-principals-comments/{session}/{class}/{exam}', [Examination::class, 'FetchPrincipalsCommentToEdit']);
        Route::get('/get-teachers-comments/{session}/{class}/{exam}', [Examination::class, 'FetchTeachersCommentToEdit']);
        Route::get('/get-principals-comments/{session}/{class}/{exam}', [Examination::class, 'FetchPrincipalsCommentToEdit']);
        Route::patch('/teachers-comment', [Examination::class, 'UpdateTeachersComment']);
        // Comment

        // Appraisal
        Route::post('/appraisal', [Examination::class, 'CreateAppraisal']);
        // Appraisal

        // Online Student application
        Route::get('/applications', [StudentApplication::class, 'FetchAllApplications']);
        Route::delete('/delete-application/{app_num}', [StudentApplication::class, 'DeleteApplication']);
    }
);

// Auth
Route::post('/auth/token', [MobileAuth::class, 'store']);
Route::post('/register', [MobileAuth::class, 'register']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});


// Student Application
Route::post('/submit-application', [StudentApplication::class, 'SubmitApplication']);
Route::get('/application/{app_num}', [StudentApplication::class, 'FetchApplication']);
Route::patch('/app-student-passport/{id}', [StudentApplication::class, 'UploadStudentPassport']);
// --path=database/migrations/2024_02_16_192835_create_appliactions_table.php
