<?php

use App\Http\Controllers\Admin\Academics;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\Examination;
use App\Http\Controllers\Admin\Profiles;
use App\Http\Controllers\Admin\Schedule;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Main\MainFunctions;
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
    function ()
    {
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

        Route::get('/all-staff', [Profiles::class, 'AllStaff']);
        Route::get('/all-parent', [Profiles::class, 'AllParent']);
        Route::get('/all-admin', [Profiles::class, 'AllAdmin']);
        Route::get('/students/{classes}', [Profiles::class, 'GetStudents']);

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
        Route::get('/subjects/{id}', [Academics::class, 'GetSubjectById']);
        Route::delete('/subjects/{id}', [Academics::class, 'DeleteSubject']);

        Route::get('/term', [Academics::class, 'GetTerm']);
        Route::post('/session', [Academics::class, 'AddSession']);
        Route::get('/session', [Academics::class, 'GetSession']);
        Route::delete('/session/{id}', [Academics::class, 'DeleteSession']);
        // Academics


        // Examination
        Route::get('/exam', [Examination::class, 'GetExam']);
        Route::post('/first-term-exam', [Examination::class, 'FirstTermResult']);
        Route::get('/get-result/{session}/{class}/{exam}/{subject}', [Examination::class, 'FetchResultToEdit']);
        Route::patch('/first-term-result', [Examination::class, 'UpdateFirstTermResult']);
        // Examination

        // Comment
        Route::post('/comment', [Examination::class, 'CreateComment']);
        // Comment

         // Appraisal
         Route::post('/appraisal', [Examination::class, 'CreateAppraisal']);
         // Appraisal
    }
);

Route::post('/auth/token', [MobileAuth::class, 'store']);
Route::post('/register', [MobileAuth::class, 'register']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});
